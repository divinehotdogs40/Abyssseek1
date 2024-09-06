import requests
import json
import mysql.connector
import re
import sys
import os
import pytz
import datetime
import re
import http.client

userID = sys.argv[1]
EmailInput = str(sys.argv[2])
Email = str(sys.argv[3])

manila_timezone = pytz.timezone('Asia/Manila')
current_datetime = datetime.datetime.now(manila_timezone).replace(microsecond=0, second=0)
current_time = current_datetime.strftime("%I:%M %p")
current_date = current_datetime.strftime("%B %d, %Y")
Time = current_date + " - " + current_time
    

os.makedirs(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/', exist_ok=True)

with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/result.txt', 'w') as file:
    file.write("")


connR = http.client.HTTPSConnection("lookup-contact.p.rapidapi.com")

headers = {
    'x-rapidapi-key': "1047bda404msh34ee7f8f7476407p14a864jsn056ee85ec1c5",
    'x-rapidapi-host': "lookup-contact.p.rapidapi.com"
}

inputForAPI = "/email-to-linkedin?email=" + EmailInput

connR.request("GET", inputForAPI, headers=headers)
res = connR.getresponse()
data = res.read().decode('utf-8')  # Decode response to string

try:
    data = json.loads(data)
except json.JSONDecodeError as e:
    raise ValueError(f"Error decoding JSON: {e}")

    
with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/result.txt', 'w') as f:
    f.write(f"{data}")
    
    
# Connect to the MySQL database
connection = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='Abyssseek',
    charset='utf8mb4'
)
    
try:
    cursor = connection.cursor()
    

    if data:
        
        # Ensure data['data'] is a dictionary
            if data['data'] is None:
                data['data'] = {}

            # List of all required keys
            required_keys = [
                'displayName', 'firstName', 'lastName', 'username', 'headline', 'about', 
                'phoneNumbers', 'companyName', 'location', 'photoUrl', 'linkedInUrl', 
                'schools', 'positions', 'skills', 'isPublic'
            ]

            # Ensure all required keys are present in the dictionary
            for key in required_keys:
                if key not in data['data']:
                    data['data'][key] = ""

            # Replace any missing or None values with an empty string
            for key, value in data['data'].items():
                if value is None:
                    data['data'][key] = ""
                elif isinstance(value, str):
                    data['data'][key] = value.replace("'", "").replace('"', '').replace('[', '').replace(']', '').replace(',', '').replace('{', '').replace('}', '')

            # Convert complex fields to JSON strings
            data['data']['phoneNumbers'] = json.dumps(data['data']['phoneNumbers']) if data['data']['phoneNumbers'] else ""
            data['data']['schools'] = json.dumps(data['data']['schools']) if data['data']['schools'] else ""
            data['data']['positions'] = json.dumps(data['data']['positions']) if data['data']['positions'] else ""
            data['data']['skills'] = json.dumps(data['data']['skills']) if data['data']['skills'] else ""

            # Check and convert boolean values and None where needed
            positions = data['data']['positions']
            isPublic = data['data']['isPublic'] if 'isPublic' in data['data'] else ""

            # Define SQL query
            sql = """INSERT INTO history_lookup_linkedin (
                        DisplayName, FirstName, LastName, Username, Headline, About, PhoneNumbers,
                        CompanyName, Location, PhotoURL, LinkedInURL, Schools, Positions, Skills, IsPublic,
                        Email, Time, EmailInput
                    ) VALUES (
                        %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
                    )"""

            # Sample values for Email, Time, and EmailInput

            # Execute SQL query
            cursor.execute(sql, (
                data['data']['displayName'], data['data']['firstName'], data['data']['lastName'], data['data']['username'],
                data['data']['headline'], data['data']['about'], data['data']['phoneNumbers'],
                data['data']['companyName'], data['data']['location'], data['data']['photoUrl'],
                data['data']['linkedInUrl'], data['data']['schools'],
                positions, data['data']['skills'], isPublic,
                Email, Time, EmailInput
            ))
    
            # Commit the changes
            connection.commit()
    else:
        print("No data to insert.")
    
except mysql.connector.Error as e:
    print(f"Error executing MySQL query: {e}")
    
finally:
    # Close the cursor and connection
    cursor.close()
    connection.close()
        
if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/control.txt'):
    os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/control.txt')


#except Exception as e:
#    connection = mysql.connector.connect(
#        host='localhost',
#        user='root',
#        password='',
#        database='Abyssseek',
#        charset='utf8mb4'
#    )
#    cursor = connection.cursor()
#    cursor.execute("INSERT INTO history_lookup_linkedin (Email, Time, DisplayName, EmailInput) VALUES (%s, %s, %s, %s)", (Email, Time, "", EmailInput))
#    connection.commit()
#    cursor.close()
#    connection.close()
#
#    print(f"Error {e}")
#    sys.exit()
#    
#finally:
#    print("Exiting program")
#    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/control.txt'):
#        os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/control.txt')
