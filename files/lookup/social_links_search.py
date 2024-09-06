import requests
import sys
import os
import re
import mysql.connector
import pytz
import datetime
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
    os.makedirs(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/', exist_ok=True)
    
    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/control.txt'):
        print("Already running!")
        raise
        
    else:
        with open(f"C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/control.txt", 'w') as f:
            f.write("Initializing...")

    with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/result.txt', 'w') as file:
        file.write("")

    url = "https://social-links-search.p.rapidapi.com/search-social-links"

    querystring = {
        "query": name,
        "social_networks": "facebook,tiktok,instagram,snapchat,twitter,youtube,linkedin,github,pinterest"
    }
    headers = {
        "X-RapidAPI-Key": "1047bda404msh34ee7f8f7476407p14a864jsn056ee85ec1c5",
        "X-RapidAPI-Host": "social-links-search.p.rapidapi.com"
    }

    rawResponse = requests.get(url, headers=headers, params=querystring)
    content = rawResponse.json()
    content = str(content)

    url_pattern = r"(https?://\S+)"
    urls = re.findall(url_pattern, content)

    def remove_special_characters(url):
        return url.replace("'", "").replace(",", "").replace("[", "").replace("]", "")

    urls = [remove_special_characters(url) for url in urls]

    data = {}

    for url in urls:
        domain = re.search(r"https?://(?:www\.)?([^/]+)", url).group(1)
        if domain in data:
            data[domain].append(url)
        else:
            data[domain] = [url]

    def print_full_links(data):
        for key, links in data.items():
            if not os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/result.txt'):
                with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/result.txt', 'w') as file:
                    file.write("")

            with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/result.txt', 'a') as file:
                file.write(f"{key}:\n")
                links_str = "\n".join(links)
                file.write(f"{links_str}\n")

        
                

    print_full_links(data)

    db_config = {
        'user': 'root',
        'password': '',
        'host': 'localhost',
        'database': 'abyssseek'
    }

    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()

    social_links = {
        'facebook.com': 'Facebook',
        'instagram.com': 'Instagram',
        'twitter.com': 'Twitter',
        'linkedin.com': 'LinkedIn',
        'youtube.com': 'YouTube',
        'music.youtube.com': 'YouTubeMusic',
        'pinterest.com': 'Pinterest',
        'tiktok.com': 'Tiktok'
    }

    links_data = {key: '' for key in social_links.values()}
    links_data['Email'] = Email
    links_data['Time'] = Time
    links_data['Name'] = nameSaves

    with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/result.txt', 'r') as file:
        current_domain = None
        for line in file:
            line = line.strip()
            if line.endswith(':'):
                current_domain = line[:-1]
            elif line and current_domain in social_links:
                column_name = social_links[current_domain]
                if links_data[column_name]:
                    links_data[column_name] += ', ' + line
                else:
                    links_data[column_name] = line

    columns = ', '.join(links_data.keys())
    placeholders = ', '.join(['%s'] * len(links_data))
    values = tuple(links_data.values())

    cursor.execute(f'''
        INSERT INTO history_lookup_sociallinkssearch ({columns})
        VALUES ({placeholders})
    ''', values)

    conn.commit()
    conn.close()
    
    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/control.txt'):
        os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/control.txt')

    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/control.txt'):
        os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/control.txt')

except Exception as e:
    print(f"Error {e}")
    sys.exit()
    
finally:
    print("Exiting program")
    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/control.txt'):
        os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/linkedin/control.txt')
    
    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/control.txt'):
        os.remove(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/control.txt')

    with open(f'C:/xampp/htdocs/abyssseek/files/lookup/data/{userID}/sociallinkssearch/result.txt', 'a') as file:
            file.write(f"\n Query Input: {name}\n")

