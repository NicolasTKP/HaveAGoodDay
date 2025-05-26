from sklearn.linear_model import LinearRegression
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.compose import ColumnTransformer
from sklearn.pipeline import Pipeline
from sklearn.ensemble import RandomForestClassifier, RandomForestRegressor
from sklearn.metrics import accuracy_score, mean_squared_error
import pandas as pd
from sklearn.preprocessing import StandardScaler
from joblib import dump
import numpy as np
from sklearn.tree import DecisionTreeRegressor
from m5py import M5Prime
import matplotlib.pyplot as plt
from mpl_toolkits.mplot3d import Axes3D


def getData(x):
    file = open(x, 'r', encoding='utf-8')
    lines = file.readlines()

    data = []
    current_entry = {}
    for line in lines:
        line = line.strip()
        if line == '':
            if current_entry:
                data.append(current_entry)
                current_entry = {}
        else:
            parts = line.split(': ', 1)
            if len(parts) == 2:
                key, value = parts
                current_entry[key] = value

    if current_entry:
        data.append(current_entry)

    df = pd.DataFrame(data)
    df = pd.get_dummies(df, columns=['Weather'])

    direction_map = {
        'n': 0, 'ne': 1, 'e': 2, 'se': 3,
        's': 4, 'sw': 5, 'w': 6, 'wnw': 7,
        'wsw': 8, 'nnw': 9, 'sse': 10, 'ese': 11,
        'nne': 12, 'ene': 13, 'nw': 14
    }
    # Map the values to a new column
    df['Wind Direction Numeric'] = df['Wind Direction'].map(direction_map)

    # Optionally, drop the original column
    df.drop(columns=['Wind Direction'], inplace=True)

    # Rename the new column to match the original column name if needed
    df.rename(columns={'Wind Direction Numeric': 'Wind_Direction'}, inplace=True)

    hour = df['Time']
    df['Time_Zone'] = None

    for i, value in enumerate(hour):
        timeZone = timeProcess(int(value[1]), int(value[9]))
        df.iloc[i, df.columns.get_loc('Time_Zone')] = timeZone
    df = df.drop('Time', axis=1)

    date = df['Date']
    dateList = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december']
    for i, value in enumerate(date):
        dateValue = value.split(' ')

        month = dateValue[1]
        monthValue = str((dateList.index(month))+1)
        dateValue = ''.join(dateValue)
        dateValue = dateValue.replace(month, '0'+monthValue)
        if len(dateValue) == 7:
            dateValue = '0'+dateValue
        df.iloc[i, df.columns.get_loc('Date')] = dateValue
    print(df)
    return df

def modelTraining(df):
    X = df.drop(columns=['Date', 'Wind_Direction', 'Weather_fog', 'Weather_haze', 'Weather_light rain passing clouds',
                         'Weather_light rain scattered clouds',
                         'Weather_passing clouds', 'Weather_rain passing clouds',
                         'Weather_rain showers scattered clouds',
                         'Weather_scattered clouds', 'Weather_thunderstorms passing clouds',
                         'Weather_thunderstorms scattered clouds'])
    y = df[['Weather_fog', 'Weather_haze', 'Weather_light rain passing clouds', 'Weather_light rain scattered clouds',
            'Weather_passing clouds', 'Weather_rain passing clouds', 'Weather_rain showers scattered clouds',
            'Weather_scattered clouds', 'Weather_thunderstorms passing clouds',
            'Weather_thunderstorms scattered clouds']]

    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

    model = RandomForestRegressor(n_estimators=100, random_state=42)

    model.fit(X_train, y_train)

    dump(model, 'weather_model.pkl')

    y_pred = model.predict(X_test)

    mse = mean_squared_error(y_test, y_pred)
    print(f'Accuracy: {mse}')

def timeProcess(start, end):
    if start == 0 and end == 6:
        return 0
    elif start == 6 and end == 2:
        return 1
    elif start == 2 and end == 8:
        return 2
    elif start == 8 and end == 0:
        return 3

def missingDataModelTraining(df):
    # # Dropping the target column from the feature set
    # X = df.drop(columns=['Date', 'Wind_Direction', 'Weather_fog', 'Weather_haze', 'Weather_light rain passing clouds',
    #                      'Weather_light rain scattered clouds', 'Weather_passing clouds', 'Weather_rain passing clouds',
    #                      'Weather_rain showers scattered clouds', 'Weather_scattered clouds',
    #                      'Weather_thunderstorms passing clouds',
    #                      'Weather_thunderstorms scattered clouds'])
    # y = df[['Max_Temperature', 'Min_Temperature']]

    # Assuming df is your preprocessed
    for col in df.columns:
        df[col] = pd.to_numeric(df[col], errors='coerce')

    columns_to_predict = ['Max_Temperature', 'Min_Temperature', 'Humidity', 'Barometer', 'Wind', 'Time_Zone']
    mses = {}

    for column in columns_to_predict:
        X = df.drop(
            columns=['Date', 'Wind_Direction', 'Weather_fog', 'Weather_haze', 'Weather_light rain passing clouds',
                     'Weather_light rain scattered clouds', 'Weather_passing clouds', 'Weather_rain passing clouds',
                     'Weather_rain showers scattered clouds', 'Weather_scattered clouds',
                     'Weather_thunderstorms passing clouds',
                     'Weather_thunderstorms scattered clouds', column])
        y = df[column]

        # Reset index to ensure consistent indexing
        X.reset_index(drop=True, inplace=True)
        y.reset_index(drop=True, inplace=True)

        # Split the data into training and testing sets
        X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
        X_train.reset_index(drop=True, inplace=True)
        y_train.reset_index(drop=True, inplace=True)
        X_test.reset_index(drop=True, inplace=True)
        y_test.reset_index(drop=True, inplace=True)

        m5_tree = M5Prime()
        m5_tree.fit(X_train, y_train)
        dump(m5_tree, f'{column}_model.pkl')

        y_pred = m5_tree.predict(X_test)
        mse = mean_squared_error(y_test, y_pred)
        mses[column] = mse
        print(f'Mean Squared Error for {column}: {mse}')

        # Plot actual vs predicted values with the same color for both actual and predicted
        plt.figure(figsize=(10, 6))
        plt.scatter(y_test, y_pred, alpha=0.5, c='blue')  # Use blue for both actual and predicted
        plt.xlabel('Actual ' + column)
        plt.ylabel('Predicted ' + column)
        plt.title('Actual vs Predicted ' + column)
        plt.plot([y.min(), y.max()], [y.min(), y.max()], 'k--', lw=2)  # Diagonal line for reference
        plt.show()



df = getData('cleaned_weatherData.txt')
modelTraining(df)
# missingDataModelTraining(df)



