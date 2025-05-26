import pandas as pd
from m5py import M5Prime
import warnings
def timeProcess(start, end):
    if start == 0 and end == 6:
        return 0
    elif start == 6 and end == 2:
        return 1
    elif start == 2 and end == 8:
        return 2
    elif start == 8 and end == 0:
        return 3
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
    df.dropna(inplace=True)
    print(df)
    return df
# Sample dataset

df = getData('cleaned_weatherData.txt')
# Convert columns to numeric types
for col in df.columns:
    df[col] = pd.to_numeric(df[col], errors='coerce')

# Separate features and target
X = df[['Min_Temperature', 'Barometer', 'Wind', 'Time_Zone']]
y = df['Humidity']

# Reset index to ensure consistent indexing
X.reset_index(drop=True, inplace=True)
y.reset_index(drop=True, inplace=True)

print(X)
print(y)

# Initialize the M5 model
model = M5Prime()

# Train the model
model.fit(X, y)

# Sample new data with feature names
new_data = pd.DataFrame({
    'Min_Temperature': [23, 25],
    'Barometer': [1010, 1025],
    'Wind': [2, 3],
    'Time_Zone': [1, 2]
})

# Suppress specific warning
with warnings.catch_warnings():
    warnings.simplefilter("ignore", UserWarning)
    predictions = model.predict(new_data)

print(predictions)

