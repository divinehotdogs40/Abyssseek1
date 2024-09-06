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

conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="abyssseek"
    )

placeholderA = "Available!"
placeholderS = "STOPPED"
placeholderR = "RUNNING"

found_urls = []
continue_crawling = False
urls_to_crawl = []
urls_to_crawl_all = []
found_count = 0
not_found_count = 0
DBContribution = 0
TotalCrawls3 = 0
DBContributionFiltered = 0

manila_timezone = pytz.timezone('Asia/Manila')
current_datetime = datetime.datetime.now(manila_timezone).replace(microsecond=0, second=0)
current_time = current_datetime.strftime("%I:%M %p")
current_date = current_datetime.strftime("%B %d, %Y")
filteredTime = current_date + " - " + current_time

#initial_url = str("quotes.toscrape.com")
#Link = str("quotes.toscrape.com")
#Keyword = str("the")
#limitCrawls = "3"
#searchMode = "Partial"
#emailEntry = "1@gmail.com"

try:
    initial_url = str(sys.argv[1])
    Link = str(sys.argv[1])
    Keyword = str(sys.argv[2])
    limitCrawls = sys.argv[3]
    searchMode = sys.argv[4]
    emailEntry = str(sys.argv[5])

    if not initial_url or not Link or not Keyword or not limitCrawls:
        sys.exit()

    
    cursor = conn.cursor()

    cursor.execute('''CREATE TABLE IF NOT EXISTS pages (id INT AUTO_INCREMENT PRIMARY KEY, url TEXT, content TEXT)''')
    conn.commit()

    cursor.execute("SELECT crawlerstatus FROM webcrawler_status WHERE Email = %s", (emailEntry,))
    result = cursor.fetchone()

    if result is None:
        sql = "INSERT INTO webcrawler_status (Email, crawlerstatus) VALUES (%s, %s)"
        values = (emailEntry, placeholderA)
        cursor.execute(sql, values)
        conn.commit()
        cursor.execute("SELECT crawlerstatus FROM webcrawler_status WHERE Email = %s", (emailEntry,))
        result = cursor.fetchone()

    if result is not None:
        result = ', '.join(str(x) for x in result)
        if result == "Available!" or "STOPPED":
            cursor.execute("UPDATE webcrawler_status SET crawlerstatus = %s WHERE Email = %s", (placeholderR, emailEntry))
            pass
        else:
            sys.exit()
    else:
        sys.exit()

    cursor.execute("UPDATE webcrawler_status SET DBContribution = 0 WHERE Email = %s", (emailEntry,))
    cursor.execute("UPDATE webcrawler_status SET foundcount = 0 WHERE Email = %s", (emailEntry,))
    cursor.execute("UPDATE webcrawler_status SET notfoundcount = 0 WHERE Email = %s", (emailEntry,))
    cursor.execute("UPDATE webcrawler_status SET found_links = '' WHERE Email = %s", (emailEntry,))
    cursor.execute("UPDATE webcrawler_status SET links_detected = '' WHERE Email = %s", (emailEntry,))
    cursor.execute("UPDATE webcrawler_status SET ErrorMessage = '' WHERE Email = %s", (emailEntry,))
    cursor.execute("UPDATE webcrawler_status SET Viewing_URL = '' WHERE Email = %s", (emailEntry,))
    cursor.execute("UPDATE webcrawler_status SET Viewing_Response = '' WHERE Email = %s", (emailEntry,))

    conn.commit()

    limitCrawls = int(limitCrawls)
    if limitCrawls > 10000:
        limitCrawls = 10000
    
    chrome_options = Options()
    chrome_options.add_argument("--headless")
    chrome_options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36")
    service = Service('C:/xampp/htdocs/abyssseek/files/webcrawler/chromedriver.exe')
    driver = webdriver.Chrome(service=service, options=chrome_options)

    def crawl(url):
        
        try:
            global domain, continue_crawling, placeholderA, placeholderS, placeholderR, found_urls, continue_crawling, urls_to_crawl, urls_to_crawl_all, found_count, not_found_count, DBContribution, TotalCrawls3, DBContributionFiltered, filteredTime, initial_url, Link, Keyword, limitCrawls, searchMode, emailEntry

            if not continue_crawling:
                return

            if url in urls_to_crawl_all:
                return

            urls_to_crawl_all.append(url)
            urls_to_crawl_all = list(set(urls_to_crawl_all))

            driver.get(url)

            time.sleep(random.uniform(0.5, 2)) #Delay for realism (para sa ibang website na may anti-bot)

            driver.execute_script("window.scrollTo(0, document.body.scrollHeight);") # Scroll to the bottom of the page
            time.sleep(random.uniform(0.5, 2))

            all_text = driver.execute_script("return document.documentElement.innerText")

            if isinstance(all_text, str):
                all_textRaw = str(all_text)
            else:
                all_textRaw = " "

            ###INITIAL SETUP NG DATABASE
            cursor = conn.cursor()

            ###FOR VIEWING TAB
            url_forViewing = "URL: <span style='text-decoration: underline;'>" + url + "</span>"
            all_textRaw_forViewing = '<span style="color: #FFAA00;">AWC:\\abyssseek\\crawler></span> RESPONSE: ' + all_textRaw

            ###FOR URL FETCHING (Right side ng website)
            cursor.execute("UPDATE webcrawler_status SET Viewing_URL = %s WHERE Email = %s", (url_forViewing, emailEntry))
            conn.commit()

            ###FOR RESPONSE FETCHING (Right side ng website)
            cursor.execute("UPDATE webcrawler_status SET Viewing_Response = %s WHERE Email = %s", (all_textRaw_forViewing, emailEntry))
            conn.commit()

            title = driver.title if driver.title else 'No Title'
            keyword_found = False
            Keyword = Keyword.lower()
            all_text = all_textRaw.lower()
            try:
                cursor = conn.cursor()
                cursor.execute("SELECT id FROM pages WHERE url = %s", (url,))
                result = cursor.fetchone()
                if result:
                    cursor.execute("UPDATE pages SET content = %s WHERE url = %s", (all_text, url))
                else:
                    cursor.execute("INSERT INTO pages (url, content) VALUES (%s, %s)", (url, all_text))
                    cursor.execute("UPDATE webcrawler_status SET DBContribution = DBContribution + 1 WHERE Email = %s", (emailEntry,))
                conn.commit()

            except Exception as e:
                print(f"Failed to save data for {url}: {e}")

            if searchMode == "Exact":
                if re.search(rf'\b{re.escape(Keyword)}\b', all_text):
                    print(f"Keyword ({Keyword}) found in URL: {url}")
                    keyword_found = True
                    cursor.execute("SELECT found_links FROM webcrawler_status WHERE Email = %s", (emailEntry,))
                    link_contents_unsplitted = cursor.fetchone()
                    link_contents_unsplitted = ', '.join(str(x) for x in link_contents_unsplitted)
                    link_contents = link_contents_unsplitted.split()
                    if url in link_contents:
                        not_found_count += 1
                        cursor.execute("UPDATE webcrawler_status SET notfoundcount = notfoundcount + 1 WHERE Email = %s", (emailEntry,))
                    else:
                        if not url == initial_url:
                            found_count += 1
                            cursor.execute("UPDATE webcrawler_status SET foundcount = foundcount + 1 WHERE Email = %s", (emailEntry,))
                            cursor.execute("UPDATE webcrawler_status SET found_links = CONCAT(%s, '\n', found_links) WHERE Email = %s", (url, emailEntry))
                    conn.commit()

            elif searchMode == "Partial":
                if Keyword in all_text:
                    print(f"Keyword ({Keyword}) found in URL: {url}")
                    keyword_found = True
                    cursor.execute("SELECT found_links FROM webcrawler_status WHERE Email = %s", (emailEntry,))
                    link_contents_unsplitted = cursor.fetchone()
                    link_contents_unsplitted = ', '.join(str(x) for x in link_contents_unsplitted)
                    link_contents = link_contents_unsplitted.split()
                    if url in link_contents:
                        not_found_count += 1
                        cursor.execute("UPDATE webcrawler_status SET notfoundcount = notfoundcount + 1 WHERE Email = %s", (emailEntry,))
                    else:
                        if not url == initial_url:
                            found_count += 1
                            cursor.execute("UPDATE webcrawler_status SET foundcount = foundcount + 1 WHERE Email = %s", (emailEntry,))
                            cursor.execute("UPDATE webcrawler_status SET found_links = CONCAT(%s, '\n', found_links) WHERE Email = %s", (url, emailEntry))

            if not keyword_found:
                print(f"No matching keyword found in URL: {url}")
                not_found_count += 1
                cursor.execute("UPDATE webcrawler_status SET notfoundcount = notfoundcount + 1 WHERE Email = %s", (emailEntry,))
                conn.commit()

            for link in driver.find_elements(By.TAG_NAME, 'a'):
                href = link.get_attribute('href')
                if href:
                    full_url = urljoin(url, href)
                    parsed_url = urlparse(full_url)
                    if parsed_url.netloc == domain and full_url not in [u['url'] for u in found_urls]:
                        urls_to_crawl.append(full_url)

                        ###FOR REMOVING URLS IF A URL IN URLS_TO_CRAWL EXISTS IN URLS_TO_CRAWL_ALL
                        for urlClear in urls_to_crawl[:]:
                            if urlClear in urls_to_crawl_all:
                                urls_to_crawl.remove(urlClear)

        except:
            pass
                    
        
        

    def format_and_sort_urls(url_string):
        url_string = url_string.replace('[', '').replace(']', '')
        urls = url_string.split(',')
        unique_urls = list(set(urls))
        sorted_urls = sorted(unique_urls)
        formatted_urls = '\n'.join(url.strip() for url in sorted_urls)
        return formatted_urls
    
    def crawl_next_url():
        global domain, continue_crawling, placeholderA, placeholderS, placeholderR, found_urls, continue_crawling, urls_to_crawl, urls_to_crawl_all, found_count, not_found_count, DBContribution, TotalCrawls3, DBContributionFiltered, filteredTime, initial_url, Link, Keyword, limitCrawls, searchMode, emailEntry
        save_history()

        while urls_to_crawl and continue_crawling:
            urls_to_crawl = list(set(urls_to_crawl))

            url = urls_to_crawl.pop(0)

            

            totalCrawls = found_count + not_found_count
            links = str(urls_to_crawl)

            cursor = conn.cursor()
            cursor.execute("SELECT links_detected FROM webcrawler_status WHERE Email = %s", (emailEntry,))
            oldLinks = cursor.fetchone()
            oldLinks = ', '.join(str(x) for x in oldLinks)
            newLinks = format_and_sort_urls(links)
            addLinks = oldLinks + "\n" + newLinks 

            urls = addLinks.split("\n")
            unique_urls = list(set(urls))

            unique_addLinks = "\n".join(unique_urls)
            cursor.execute("UPDATE webcrawler_status SET links_detected = %s WHERE Email = %s", (unique_addLinks, emailEntry))
            conn.commit()

            if totalCrawls >= limitCrawls:
                break

            cursor.execute("SELECT crawlerstatus FROM webcrawler_status WHERE Email = %s", (emailEntry,))
            controlInni = cursor.fetchone()
            controlInni = ', '.join(str(x) for x in controlInni)

            if controlInni == "STOPPED":
                break
            
            crawl(url)

        cursor.execute("SELECT foundcount FROM webcrawler_status WHERE Email = %s", (emailEntry,))
        TotalCrawls1 = cursor.fetchone()
        TotalCrawls1 = ', '.join(str(x) for x in TotalCrawls1)
        TotalCrawls1 = int(TotalCrawls1)

        cursor.execute("SELECT notfoundcount FROM webcrawler_status WHERE Email = %s", (emailEntry,))
        TotalCrawls2 = cursor.fetchone()
        TotalCrawls2 = ', '.join(str(x) for x in TotalCrawls2)
        TotalCrawls2 = int(TotalCrawls2)
        
        TotalCrawls3 = TotalCrawls1 + TotalCrawls2

        cursor.execute("SELECT DBContribution FROM webcrawler_status WHERE Email = %s", (emailEntry,))
        DBContributionFiltered = cursor.fetchone()
        DBContributionFiltered = ', '.join(str(x) for x in DBContributionFiltered)
        DBContributionFiltered = int(DBContributionFiltered)

        cursor.execute("INSERT INTO history_webcrawler (Email, Time, Link, Keyword, LimitCrawls, SearchMode, TotalCrawls, DBContribution) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", (emailEntry, filteredTime, Link, Keyword, limitCrawls, searchMode, TotalCrawls3, DBContributionFiltered))
        cursor.execute("UPDATE webcrawler_status SET crawlerstatus = %s WHERE Email = %s", (placeholderA, emailEntry,))
        conn.commit()
        conn.close()

        print("HISTORY SAVED")

        sys.exit()

    def search_content():
        global domain, continue_crawling, placeholderA, placeholderS, placeholderR, found_urls, continue_crawling, urls_to_crawl, urls_to_crawl_all, found_count, not_found_count, DBContribution, TotalCrawls3, DBContributionFiltered, filteredTime, initial_url, Link, Keyword, limitCrawls, searchMode, emailEntry

        found_count = 0
        not_found_count = 0
        DBContribution = 0

        continue_crawling = True

        if not initial_url.startswith("http://") and not initial_url.startswith("https://"):
            initial_url = "https://" + initial_url

        if not initial_url.endswith("/"):
            initial_url += "/"

        try:
            response = driver.get(initial_url)

        except Exception as e:
            raise ValueError(f"Driver Error: {e}")
        
        urls_to_crawl.clear()
        urls_to_crawl.append(initial_url)
        domain = urlparse(initial_url).netloc
        crawl_next_url()

    def save_history():
        global domain, continue_crawling, placeholderA, placeholderS, placeholderR, found_urls, continue_crawling, urls_to_crawl, urls_to_crawl_all, found_count, not_found_count, DBContribution, TotalCrawls3, DBContributionFiltered, filteredTime, initial_url, Link, Keyword, limitCrawls, searchMode, emailEntry

        cursor.execute("SELECT search_history FROM webcrawler_status WHERE Email = %s", (emailEntry,))
        searchHistoryValue = cursor.fetchone()
        searchHistoryValue = ', '.join(str(x) for x in searchHistoryValue)
        searchHistoryValueNew = f"Time: {current_date} - {current_time}\nLink: {Link}\nKeyword: {Keyword}\nLimit: {limitCrawls}\nSearch Mode: {searchMode} Keyword Match\n\n{searchHistoryValue}"
        
        cursor.execute("UPDATE webcrawler_status SET search_history = %s WHERE Email = %s", (searchHistoryValueNew, emailEntry))

        conn.commit()


    

    search_content()

    driver.quit()

except Exception as e:
    print(f"Error {e}")
    raisedError = str(e)

    cursor.execute("INSERT INTO history_webcrawler (Email, Time, Link, Keyword, LimitCrawls, SearchMode, TotalCrawls, DBContribution) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", (emailEntry, filteredTime, Link, Keyword, limitCrawls, searchMode, TotalCrawls3, DBContributionFiltered))
    cursor.execute("UPDATE webcrawler_status SET crawlerstatus = %s WHERE Email = %s", (placeholderA, emailEntry))
    
    cursor.execute("UPDATE webcrawler_status SET crawlerstatus = %s WHERE Email = %s", (placeholderA, emailEntry))
    cursor.execute("UPDATE webcrawler_status SET ErrorMessage = %s WHERE Email = %s", (raisedError, emailEntry))
    conn.commit()

    ###INITIAL SETUP NG DATABASE
    cursor = conn.cursor()

    ###FOR VIEWING TAB
    ErrorType = "ERROR: FATAL ERROR!"
    ErrorResponse = '<span style="color: #FFAA00;">AWC:\\abyssseek\\crawler></span>  DETAILS: ' + raisedError

    ###FOR URL FETCHING (Right side ng website)
    cursor.execute("UPDATE webcrawler_status SET Viewing_URL = %s WHERE Email = %s", (ErrorType, emailEntry))
    conn.commit()

    ###FOR RESPONSE FETCHING (Right side ng website)
    cursor.execute("UPDATE webcrawler_status SET Viewing_Response = %s WHERE Email = %s", (ErrorResponse, emailEntry))
    conn.commit()



    conn.commit()
    conn.close()

    if driver:
        driver.quit()
    
    
finally:
    print("Exiting program")