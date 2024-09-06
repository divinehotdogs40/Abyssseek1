import requests
import json
import sys
import datetime
import pytz
import mysql.connector
import os
import re


userID = sys.argv[1]
input_data = str(sys.argv[2])
Email = str(sys.argv[3])

manila_timezone = pytz.timezone('Asia/Manila')
current_datetime = datetime.datetime.now(manila_timezone).replace(microsecond=0, second=0)
current_time = current_datetime.strftime("%I:%M %p")
current_date = current_datetime.strftime("%B %d, %Y")
Time = current_date + " - " + current_time


try:

    os.makedirs(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/skype/', exist_ok=True)
    
    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/skype/control.txt'):
        print("Already running!")
        raise Exception("Process already running")

    with open(f"C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/skype/control.txt", 'w') as f:
        f.write("Initializing...")

    with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/skype/result.txt', 'w') as file:
        file.write("")

    if "@" in input_data:
        InputType = "Email"

        jsonloading = True

        url = "https://user-social-presence-checker.p.rapidapi.com/rapid-api/1.0/social-data"

        querystring = {"email": input_data,"phone": "","max-wait-time-seconds":"10","platforms":"skype"}

        headers = {
        	"X-RapidAPI-Key": "1047bda404msh34ee7f8f7476407p14a864jsn056ee85ec1c5",
        	"X-RapidAPI-Host": "user-social-presence-checker.p.rapidapi.com"
        }

        response = requests.get(url, headers=headers, params=querystring)

        json_string = str(json.dumps(response.json(), indent=4))

        with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/skype/result.txt', 'w') as file:
            file.write(json_string)

    elif input_data.isdigit():
        InputType = "Phone"

        jsonloading = True

        querydata_number = str(input_data)

        url = "https://user-social-presence-checker.p.rapidapi.com/rapid-api/1.0/social-data"

        querystring = {"phone": querydata_number,"max-wait-time-seconds":"10","platforms":"skype"}

        headers = {
        	"X-RapidAPI-Key": "844be29ebdmsh44fac3f4d8fcfbcp15a627jsn4bf818b1247e",
        	"X-RapidAPI-Host": "user-social-presence-checker.p.rapidapi.com"
        }

        response = requests.get(url, headers=headers, params=querystring)

        json_string = str(json.dumps(response.json(), indent=4))

        with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/skype/result.txt', 'w') as file:
            file.write(json_string)

    else:
        InputType = "Unknown"
        jsonloading = False


    if jsonloading:

        try:
            data = json.loads(json_string)

            platform_presence = data.get("platformPresenceFacts", [{}])[0]
            present = platform_presence.get("present", "")
            id_value = platform_presence.get("details", {}).get("id", "")
            name = platform_presence.get("details", {}).get("name", "")
            photo = platform_presence.get("details", {}).get("photo", "")
            is_business_account = platform_presence.get("details", {}).get("isBusinessAccount", "")
        
        except Exception as e:
            InputType = "Phone (Invalid)"
            data = ""
            platform_presence = ""
            present = ""
            id_value = ""
            name = ""
            photo = ""
            is_business_account = ""
            print("INVALID NUMBER!")

        

    else:
        data = ""
        platform_presence = ""
        present = ""
        id_value = ""
        name = ""
        photo = ""
        is_business_account = ""
        print("UNKNOWN INPUT!")


    # Print the extracted values
    print("Present:", present)
    print("ID:", id_value)
    print("Name:", name)
    print("Photo:", photo)
    print("Is Business Account:", is_business_account)

    # Connect to the MySQL database
    db_connection = mysql.connector.connect(
        host="localhost",
        user="root",
        database="abyssseek"
    )

    # Create a cursor object to execute SQL queries
    cursor = db_connection.cursor()

    # SQL query to insert data into the table
    sql_query = """
        INSERT INTO history_lookup_skype 
        (Email, Time, InputType, Input, Present, ProfileID, AccName, PhotoURL, IsBusinessAccount) 
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
    """

    # Execute the SQL query
    cursor.execute(sql_query, (Email, Time, InputType, input_data, present, id_value, name, photo, is_business_account))

    # Commit the changes to the database
    db_connection.commit()

    # Close the cursor and database connection
    cursor.close()
    db_connection.close()

    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/skype/control.txt'):
        os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/skype/control.txt')

except Exception as e:
    print(f"Error {e}")
    sys.exit()
    
finally:
    print("Exiting program")
    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/skype/control.txt'):
        os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/skype/control.txt')