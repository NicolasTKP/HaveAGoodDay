from joblib import load
import pandas as pd
from sklearn.impute import SimpleImputer
import sys

def main():

    model = load('weatherModels/weather_model.pkl')
    try:
        maxTemp=int(sys.argv[1])
    except:
        maxTemp = -1

    try:
        minTemp=int(sys.argv[2])
    except:
        minTemp = -1
    try:
        humidity=int(sys.argv[3])
    except:
        humidity = -1
        
    try:
        barometer=int(sys.argv[4])
    except:
        barometer = -1
    
    try:
        windSpeed=int(sys.argv[5])
    except:
        windSpeed = -1
    
    time=sys.argv[6]
    time=int(time.split(":")[0])
    if (time<=6):
        timeZone = 0
    elif (time<=12):
        timeZone = 1
    elif (time<=18):
        timeZone = 2
    else:
        timeZone = 3
    

    if (maxTemp==-1):
        maxTemp = missingData('Max_Temperature', maxTemp, minTemp, humidity, barometer, windSpeed, timeZone)
    
    if (minTemp==-1):
        minTemp = missingData('Min_Temperature', maxTemp, minTemp, humidity, barometer, windSpeed, timeZone)

    if (humidity==-1):
        humidity = missingData('Humidity', maxTemp, minTemp, humidity, barometer, windSpeed, timeZone)
    
    if (barometer==-1):
        barometer = missingData('Barometer', maxTemp, minTemp, humidity, barometer, windSpeed, timeZone)
    
    if (windSpeed==-1):
        windSpeed = missingData('Wind', maxTemp, minTemp, humidity, barometer, windSpeed, timeZone)


    data = {
        'Max_Temperature': [maxTemp],
        'Min_Temperature': [minTemp],
        'Humidity': [humidity],
        'Barometer': [barometer],
        'Wind': [windSpeed],
        'Time_Zone': [timeZone]
    }

    X_new = pd.DataFrame(data)

    proba = model.predict(X_new)
    
    for value in proba:
        for item in value:  
            print(int(item * 100))



def missingData(missingData, maxTemp, minTemp, humidity, barometer, windSpeed, timeZone):
    data = {
        'Max_Temperature': [maxTemp],
        'Min_Temperature': [minTemp],
        'Humidity': [humidity],
        'Barometer': [barometer],
        'Wind': [windSpeed],
        'Time_Zone': [timeZone]
    }

    df = pd.DataFrame(data)
    
    df = df.drop(missingData, axis=1)
    model_path = f'weatherModels/{missingData}_model.pkl'
    missingDataModel = load(model_path)
    missing_value = missingDataModel.predict(df)
    return int(missing_value)


    

if __name__ == "__main__":
    main()