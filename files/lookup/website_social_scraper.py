import requests
import sys
import os
import mysql.connector
import pytz
import datetime
import json
import re

def extract_username(inputSearch):
    # Find the position of the '@' character
    at_index = inputSearch.find('@')
    
    # Extract the substring before the '@' character
    if at_index != -1:
        username = inputSearch[:at_index]
    else:
        # If '@' is not found, return the whole email as is
        username = inputSearch
    
    return username


userID = sys.argv[1]
name = str(sys.argv[2])

name = extract_username(name)

#For any special char, excluding at sign and dot (.)
pattern = r'[^a-zA-Z0-9\s.@]'
name = re.sub(pattern, '', name)

Email = str(sys.argv[3])

nameSaves = str(sys.argv[2])

manila_timezone = pytz.timezone('Asia/Manila')
current_datetime = datetime.datetime.now(manila_timezone).replace(microsecond=0, second=0)
current_time = current_datetime.strftime("%I:%M %p")
current_date = current_datetime.strftime("%B %d, %Y")
Time = current_date + " - " + current_time

try:
    os.makedirs(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/websitesocialscraper/', exist_ok=True)
    
    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/websitesocialscraper/control.txt'):
        print("Already running!")
        raise Exception("Process already running")

    with open(f"C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/websitesocialscraper/control.txt", 'w') as f:
        f.write("Initializing...")

    with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/websitesocialscraper/result.txt', 'w') as file:
        file.write("")

    url = "https://website-social-scraper-api.p.rapidapi.com/contacts/"
    loweredName = name.lower()
    loweredName = re.sub(r'[^a-zA-Z0-9]', '', loweredName)
    queryName = loweredName.replace(" ", "") + ".com"
    queryName = "https://www." + queryName + "/"
    querystring = {"website": queryName}

    headers = {
        "X-RapidAPI-Key": "1047bda404msh34ee7f8f7476407p14a864jsn056ee85ec1c5",
        "X-RapidAPI-Host": "website-social-scraper-api.p.rapidapi.com"
    }

    rawResponse = requests.get(url, headers=headers, params=querystring)
    json_string = str(json.dumps(rawResponse.json(), indent=4))

    output = ''
    data = json.loads(json_string)

    for key, value in data.items():
        output += f"{key}:{value}\n"


    output = output.replace("[", "").replace("]", "").replace("\n\n", "\n")

    with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/websitesocialscraper/result.txt', 'w') as file:
        file.write(output)

    with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/websitesocialscraper/result.txt', 'a') as file:

        file.write(f"\n \n Query Input: {queryName}\n")

    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/websitesocialscraper/control.txt'):
        os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/websitesocialscraper/control.txt')

    data = {
        "emails": "",
        "phones": "",
        "linkedin": "",
        "twitter": "",
        "facebook": "",
        "youtube": "",
        "instagram": "",
        "github": "",
        "snapchat": "",
        "tiktok": ""
    }

    lines = output.split('\n')
    for line in lines:
        if ':' in line:
            key, value = line.split(':', 1)
            key = key.strip().lower()
            value = value.strip()
            if key in data:
                if key == 'emails':
                    # Concatenate all email addresses into a single string
                    emails_list = [email.strip() for email in value.split(',')]
                    data[key] = ', '.join(emails_list)
                else:
                    data[key] = value if value.lower() != "none" else ""



    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="abyssseek"
    )

    cursor = conn.cursor()

    sql = """
    INSERT INTO history_lookup_websitesocialscraper (
        Email, Time, Name, Emails, Phones, Facebook, Tiktok, Instagram, Snapchat, Twitter, YouTube, LinkedIn, GitHub
    ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
    """

    values = (
        Email,
        Time,
        nameSaves,
        data['emails'],
        data['phones'],
        data['facebook'],
        data['tiktok'],
        data['instagram'],
        data['snapchat'],
        data['twitter'],
        data['youtube'],
        data['linkedin'],
        data['github']
    )


    cursor.execute(sql, values)
    conn.commit()

    cursor.close()
    conn.close()

    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/control.txt'):
        os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/control.txt')

except Exception as e:
    print(f"Error: {e}")
    sys.exit()

finally:
    print("Exiting program")
    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/control.txt'):
        os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/control.txt')
