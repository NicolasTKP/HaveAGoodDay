from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.action_chains import ActionChains
import time


service = Service(executable_path="chromedriver.exe")
driver = webdriver.Chrome(service=service)
data = open('weatherData.txt', 'w', encoding='utf-8')

def scrap():
    driver.get("https://www.timeanddate.com/weather/malaysia/kuala-lumpur/historic?month=1&year=2019")
    month = driver.find_element(By.ID, 'month')
    options = month.find_elements(By.TAG_NAME, 'option')

    for x in range(3, 17):
        options[x].click()
        time.sleep(.5)
        days = driver.find_element(By.CLASS_NAME, 'weatherLinks')
        day = days.find_elements(By.TAG_NAME, 'a')
        for y in range(len(day)*4):
            timezone = driver.find_element(By.ID, 'ws_'+str(y))
            hover = ActionChains(driver).move_to_element(timezone)
            hover.perform()
            time.sleep(.1)
            info = driver.find_element(By.CLASS_NAME, 'weatherTooltip')
            date = info.find_element(By.CLASS_NAME, 'date').text
            exactDate = date.split(', ')[1]
            exactTime = date.split(', ')[2]
            temp = info.find_element(By.CLASS_NAME, 'temp').text
            temp = temp.split('/')
            maxTemp = temp[0].strip()
            minTemp = temp[1].strip()
            mid_block = info.find_element(By.CLASS_NAME, 'mid__block')
            content = mid_block.find_elements(By.TAG_NAME, 'div')
            humidity = content[0].text
            pressure = content[1].text
            windDirection = info.find_element(By.CLASS_NAME, 'windDirection').text
            right_block = info.find_element(By.CLASS_NAME, 'right__block')
            content = right_block.find_elements(By.TAG_NAME, 'div')
            windSpeed = content[1].text
            weather = info.find_element(By.CLASS_NAME, 'wdesc').text
            weather = weather.replace('.', '')

            data.write(f'''
Date: {exactDate}
Time: {exactTime}
Max Temperature: {maxTemp} °C
Min Temperature: {minTemp}
{humidity}
{pressure}
Wind Direction: {windDirection}
{windSpeed}
Weather: {weather}
''')

        month = driver.find_element(By.ID, 'month')
        options = month.find_elements(By.TAG_NAME, 'option')


    data.close()
    time.sleep(2)
    driver.quit()
scrap()
