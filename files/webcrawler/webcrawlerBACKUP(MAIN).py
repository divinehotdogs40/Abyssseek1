import requests
from urllib.parse import urlparse, urljoin
import threading
import datetime
import pytz
import sys
import os
from pathlib import Path
import signal
import time
import re
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
import random
import mysql.connector



chrome_options = Options()
chrome_options.add_argument("--headless")
chrome_options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36")
service = Service('C:/xampp/htdocs/abyssseek/files/webcrawler/chromedriver.exe')
driver = webdriver.Chrome(service=service, options=chrome_options)

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="abyssseek"
)
cursor = conn.cursor()

cursor.execute('''CREATE TABLE IF NOT EXISTS pages (id INT AUTO_INCREMENT PRIMARY KEY, url TEXT, content TEXT)''')
conn.commit()
cursor.close()

try:

    #initial_url = str("quotes.toscrape.com")
    #Link = str("quotes.toscrape.com")
    #keyword = str("test")
    #Keyword = str("test")
    #userID = "49"
    #limitCrawls = "3"
    #searchMode = "Partial"
    #emailEntry = str("1@gmail.com")

    initial_url = str(sys.argv[1])
    Link = str(sys.argv[1])
    keyword = str(sys.argv[2])
    Keyword = str(sys.argv[2])
    userID = sys.argv[3]
    limitCrawls = sys.argv[4]
    searchMode = sys.argv[5]
    emailEntry = str(sys.argv[6])

    if not initial_url or not Link or not keyword or not Keyword or not userID or not limitCrawls:
        sys.exit()



    if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/control.txt'):
        os.remove(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/control.txt')
    else:
        print(f'No control file!')
        sys.exit()



    linksDetectedFile = Path(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/links_detected.txt')
    with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/links_detected.txt', 'w') as f:
        f.write("")



    limitCrawls = int(limitCrawls)
    if limitCrawls > 10000:
        limitCrawls = 10000


    
    
    def crawl(url):
        global domain, continue_crawling, found_count, not_found_count
        if not continue_crawling:
            return

        driver.get(url)
        time.sleep(random.uniform(0.5, 2)) #Delay so that the browsing would be realistic (para sa ibang website na may anti-bot)

        

        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);") # Scroll to the bottom of the page
        time.sleep(random.uniform(0.5, 2))

        all_text = driver.execute_script("return document.documentElement.innerText")
        all_textRaw = str(all_text)
        
        title = driver.title if driver.title else 'No Title'
        keyword_found = False
        keyword = sys.argv[2]
        keyword = keyword.lower()
        all_text = all_textRaw.lower()

        try:
            cursor = conn.cursor()
            cursor.execute("SELECT id FROM pages WHERE url = %s", (url,))
            result = cursor.fetchone()
            if result:
                cursor.execute("UPDATE pages SET content = %s WHERE url = %s", (all_text, url))
            else:
                cursor.execute("INSERT INTO pages (url, content) VALUES (%s, %s)", (url, all_text))

                with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/DBContribution.txt', 'r') as f:
                    DBContribution_raw = int(f.read())
                with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/DBContribution.txt', 'w') as f:
                    DBContribution_raw += 1
                    DBContribution_raw = str(DBContribution_raw)
                    f.write(DBContribution_raw)


            conn.commit()
            print(f"result is {result}")
        except Exception as e:
            print(f"Failed to save data for {url}: {e}")

        if searchMode == "Exact":
            if re.search(rf'\b{re.escape(keyword)}\b', all_text):
                print(f"Keyword ({keyword}) found in URL: {url}")
                keyword_found = True
                with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/found_links.txt', 'r') as f:
                    link_contents_unsplitted = f.read()

                link_contents = link_contents_unsplitted.split()

                if url in link_contents:
                    not_found_count += 1
                    with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/notfoundcount.txt', 'w') as f:
                        not_found_count_converted = str(not_found_count)
                        f.write(not_found_count_converted)
                else:
                    if not url == initial_url:
                        found_count += 1

                        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/foundcount.txt', 'w') as f:
                            found_count_converted = str(found_count)
                            f.write(found_count_converted)

                        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/found_links.txt', 'w') as f:
                            f.seek(0, 0)
                            f.write(f"{url}\n{link_contents_unsplitted}")

        elif searchMode == "Partial":
            if keyword in all_text:
                print(f"Keyword ({keyword}) found in URL: {url}")
                keyword_found = True
                with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/found_links.txt', 'r') as f:
                    link_contents_unsplitted = f.read()

                link_contents = link_contents_unsplitted.split()

                if url in link_contents:
                    not_found_count += 1
                    with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/notfoundcount.txt', 'w') as f:
                        not_found_count_converted = str(not_found_count)
                        f.write(not_found_count_converted)
                else:
                    if not url == initial_url:
                        found_count += 1
                
                        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/foundcount.txt', 'w') as f:
                            found_count_converted = str(found_count)
                            f.write(found_count_converted)

                        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/found_links.txt', 'w') as f:
                            f.seek(0, 0)
                            f.write(f"{url}\n{link_contents_unsplitted}")

        if not keyword_found:
            print(f"No matching keyword found in URL: {url}")
            not_found_count += 1
            with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/notfoundcount.txt', 'w') as f:
                not_found_count_converted = str(not_found_count)
                f.write(not_found_count_converted)

        for link in driver.find_elements(By.TAG_NAME, 'a'):
            href = link.get_attribute('href')
            if href:
                full_url = urljoin(url, href)
                parsed_url = urlparse(full_url)
                if parsed_url.netloc == domain and full_url not in [u['url'] for u in found_urls]:
                    urls_to_crawl.append(full_url)

    def remove_duplicates(contentLinks):
        lines_seen = set()
        with open(contentLinks, 'r') as file_in:
            lines = file_in.readlines()
        with open(contentLinks, 'w') as file_out:
            for line in lines:
                line = line.strip()
                if line.startswith("'"):
                    line = line[1:]
                if line.endswith("'"):
                    line = line[:-1]
                if line not in lines_seen:
                    file_out.write(line + '\n')
                    lines_seen.add(line)

    def format_and_sort_urls(url_string):
        url_string = url_string.replace('[', '').replace(']', '')
        urls = url_string.split(',')
        unique_urls = list(set(urls))
        sorted_urls = sorted(unique_urls)
        formatted_urls = '\n'.join(url.strip() for url in sorted_urls)

        return formatted_urls

    def crawl_next_url():
        save_history()
        global urls_to_crawl
        while urls_to_crawl and continue_crawling:
            url = urls_to_crawl.pop(0)

            totalCrawls = found_count + not_found_count

            links = str(urls_to_crawl)
            with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/links_detected.txt', 'r') as f:
                oldLinks = f.read()

            newLinks = format_and_sort_urls(links)

            addLinks = oldLinks + "\n" + newLinks 

            with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/links_detected.txt', 'w') as f:
                f.write(addLinks)

            linksSaved = f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/links_detected.txt'
            remove_duplicates(linksSaved)

            if totalCrawls >= limitCrawls:
                break
    
            if os.path.exists(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/control.txt'):
                break
            
            crawl(url)

        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/found_links.txt', 'r') as f:
            link_contents_end = f.read()
    
            with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/control.txt', 'w') as f:
                f.write("This system is designed exclusively for use by the Armed Forces of the Philippines and is subject to strict confidentiality and security measures. It is prohibited to copy, modify, or make any alterations to the system without explicit authorization. Unauthorized access or use of this system may result in disciplinary action or legal consequences.")

        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/notfoundcount.txt', 'r') as f:
            TotalCrawls1 = int(f.read())
        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/foundcount.txt', 'r') as f:
            TotalCrawls2 = int(f.read())
        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/foundcount.txt', 'r') as f:
            TotalCrawls2 = int(f.read())

        TotalCrawls3 = TotalCrawls1 + TotalCrawls2

        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/DBContribution.txt', 'r') as f:
            DBContributionFiltered = int(f.read())

        cursor = conn.cursor()
        cursor.execute("INSERT INTO history_webcrawler (Email, Time, Link, Keyword, LimitCrawls, SearchMode, TotalCrawls, DBContribution) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", (emailEntry, filteredTime, Link, Keyword, limitCrawls, searchMode, TotalCrawls3, DBContributionFiltered))
        conn.commit()
        conn.close()

        sys.exit()

    def search_content():
        global continue_crawling, domain, found_count, not_found_count, initial_url

        found_count = 0
        not_found_count = 0
        DBContribution = 0

        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/foundcount.txt', 'w') as f:
            found_count_converted = str(found_count)
            f.write(found_count_converted)
        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/notfoundcount.txt', 'w') as f:
            not_found_count_converted = str(not_found_count)
            f.write(not_found_count_converted)
        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/DBContribution.txt', 'w') as f:
            DBContribution_converted = str(DBContribution)
            f.write(DBContribution_converted)

        if continue_crawling:
            print("Crawling is already in progress.")
            return

        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/found_links.txt', 'w') as f:
            f.write("")

        continue_crawling = True
        found_count = 0
        not_found_count = 0
        DBContribution = 0

        if not initial_url:
            print("Please enter an initial URL.")
            continue_crawling = False
            return

        if not initial_url.startswith("http://") and not initial_url.startswith("https://"):
            initial_url = "https://" + initial_url

        if not initial_url.endswith("/"):
            initial_url += "/"

        try:
            response = requests.get(initial_url)
            response.raise_for_status()
        except requests.exceptions.RequestException as e:
            print(f"Invalid URL: {initial_url}")
            continue_crawling = False
            with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/control.txt', 'w') as f:
                f.write("This system is designed exclusively for use by the Armed Forces of the Philippines and is subject to strict confidentiality and security measures. It is prohibited to copy, modify, or make any alterations to the system without explicit authorization. Unauthorized access or use of this system may result in disciplinary action or legal consequences.")
            return



        urls_to_crawl.clear()
        urls_to_crawl.append(initial_url)
        domain = urlparse(initial_url).netloc

        crawl_next_url()

    def save_history():
        global Keyword, filteredTime
        manila_timezone = pytz.timezone('Asia/Manila')
        current_datetime = datetime.datetime.now(manila_timezone).replace(microsecond=0, second=0)
        current_time = current_datetime.strftime("%I:%M %p")
        current_date = current_datetime.strftime("%B %d, %Y")

        search_history_path = Path(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/search_history.txt')
        if search_history_path.exists():
            pass
        else:
            with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/search_history.txt', 'w') as file:
                pass

        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/search_history.txt', 'r') as f:
            content = f.read()

        with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/search_history.txt', 'w') as f:
            f.seek(0, 0)
            f.write(f"Time: {current_date} - {current_time}\nLink: {Link}\nKeyword: {Keyword}\n Limit: {limitCrawls}\nSearch Mode: {searchMode} Keyword Match\n\n{content}")
        
        filteredTime = current_date + " - " + current_time

    found_urls = []
    continue_crawling = False
    urls_to_crawl = []
    found_count = 0
    not_found_count = 0
    DBContribution = 0



    search_content()

except Exception as e:
    print(f"Error {e}")
    userID = sys.argv[3]
    with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/control.txt', 'w') as f:
        f.write("This system is designed exclusively for use by the Armed Forces of the Philippines and is subject to strict confidentiality and security measures. It is prohibited to copy, modify, or make any alterations to the system without explicit authorization. Unauthorized access or use of this system may result in disciplinary action or legal consequences.")
    print("Performing final command before exiting...")
    with open(f'C:/xampp/htdocs/abyssseek/files/webcrawler/data/{userID}/stopMessage.txt', 'w') as f:
        f.write("Finished!")
    
finally:
    driver.quit()
    print("Exiting program")