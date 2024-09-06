import time
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
import webbrowser
import sys
import shutil
import os



loaderTrigger = 'assets/loaderTrigger.txt'

if os.path.exists(loaderTrigger):
    os.remove(loaderTrigger)
else:
    pass


with open(loaderTrigger, 'w') as f:
    f.write('This system is protected by the Armed Forces of the Philippines and is not to be copied, edited, duplicated, or otherwise used without explicit authorization. Any unauthorized use or distribution is strictly prohibited and may result in legal action.')

shutil.copy('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/builds/defaultSites.txt', 'C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt')

def search_username():

    check = sys.argv[1]

    if not check:
        sys.exit()

    username = check

    platforms = [
        ("Facebook", f"https://www.facebook.com/{username}"),
        ("Instagram", f"https://www.instagram.com/{username}"),
        ("YouTube", f"https://www.youtube.com/{username}"),
        ("Twitter", f"https://www.twitter.com/{username}"),
        ("Reddit", f"https://www.reddit.com/user/{username}"),
        ("Pinterest", f"https://www.pinterest.com/{username}"),
        ("Tumblr", f"https://{username}.tumblr.com/"),
        ("Snapchat", f"https://www.snapchat.com/add/{username}"),
        ("TikTok", f"https://www.tiktok.com/@{username}"),
        ("About.me", f"https://about.me/{username}"),
        ("Quora", f"https://www.quora.com/profile/{username}"),
        ("GitHub", f"https://github.com/{username}"),
        ("Behance", f"https://www.behance.net/{username}"),
        ("Dribbble", f"https://dribbble.com/{username}"),
        ("SlideShare", f"https://www.slideshare.net/{username}"),
        ("ProductHunt", f"https://www.producthunt.com/@{username}"),
        ("HackerRank", f"https://www.hackerrank.com/{username}"),
        ("CodePen", f"https://codepen.io/{username}"),
        ("Bitbucket", f"https://bitbucket.org/{username}"),
        ("Patreon", f"https://www.patreon.com/{username}"),
    ]
    

    chrome_options = Options()
    chrome_options.add_argument("--headless")
    chrome_options.add_argument("--disable-web-security")
    chrome_options.add_argument("--disable-cookie-encryption")
    chrome_options.add_argument("user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.109 Safari/537.3")



    driver = webdriver.Chrome(options=chrome_options)

    for platform, url in platforms:
        try:

            if os.path.exists('assets/loaderTrigger.txt'):
                pass
            else:
                sys.exit()

            driver.get(url)
            time.sleep(1)

            page_source = ""
            page_title = ""

            page_source = driver.page_source
            page_source = str(page_source)
            page_title = driver.title
            page_title = str(page_title)

            if platform == "Facebook":
                print("Facebook")
                if "Hindi Available Ang Content Na Ito Sa Ngayon" in page_source:
                    print("Facebook NNNNNNNN")
                    pass
                else:
                    print("Facebook FOUND")
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Facebook'):
                            lines[i] = 'Facebook ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "Instagram":
                print("Instagram")
                if '"pageID":"httpErrorPage"' in page_source:
                    print("Instagram NNNNNNNN")
                    pass
                else:
                    print("Instagram FOUND")
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Instagram'):
                            lines[i] = 'Instagram ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "YouTube":
                print("YouTube")
                if "404 Not Found" in page_source:
                    print("YouTube NNNNNNNN")
                    pass
                else:
                    print("YouTube FOUND")
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('YouTube'):
                            lines[i] = 'YouTube ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "Twitter":
                print("Twitter")
                if "This account doesn’t exist" or "page doesn’t exist" in page_source:
                    print("Twitter NNNNNNNN")
                    pass
                else:
                    print("Twitter FOUND")
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Twitter'):
                            lines[i] = 'Twitter ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "Reddit":
                print("Reddit")
                if "nobody on Reddit goes by that name" in page_source:
                    print("Reddit NNNNNNNN")
                    pass
                else:
                    print("Reddit FOUND")
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Reddit'):
                            lines[i] = 'Reddit ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "Pinterest":
                if 'data-test-id="profile-followers-link"' in page_source:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Pinterest'):
                            lines[i] = 'Pinterest ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)

                else:
                    pass

            elif platform == "Tumblr":
                if "There's nothing here" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Tumblr'):
                            lines[i] = 'Tumblr ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "Snapchat":
                if "NoContent_subtitle" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Snapchat'):
                            lines[i] = 'Snapchat ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "TikTok":
                if "css-1kls0os-PDesc emuynwa2" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('TikTok'):
                            lines[i] = 'TikTok ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "About.me":
                if "There is no one by the name" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('AboutMe'):
                            lines[i] = 'AboutMe ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "Quora":
                if "CssComponent__CssInlineComponent-sc-1oskqb9-1 TitleText___StyledCssInlineComponent-sc-1hpb63h-0  hiLnej" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Quora'):
                            lines[i] = 'Quora ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "GitHub":
                if "This is not the web page you are looking for" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('GitHub'):
                            lines[i] = 'GitHub ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "Behance":
                if "Oops! We can’t find that page." in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Behance'):
                            lines[i] = 'Behance ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "Dribbble":
                if "Whoops, that page is gone." in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Dribbble'):
                            lines[i] = 'Dribbble ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "SlideShare":
                if "is still available. Why not" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('SlideShare'):
                            lines[i] = 'SlideShare ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                
            elif platform == "ProductHunt":
                if "We seem to have lost this page" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('ProductHunt'):
                            lines[i] = 'ProductHunt ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)
                    
            elif platform == "HackerRank":
                if "We could not find the page you were looking for" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('HackerRank'):
                            lines[i] = 'HackerRank ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)

            elif platform == "CodePen":
                if "I'm afraid you've found a page that doesn't exist on CodePen" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('CodePen'):
                            lines[i] = 'CodePen ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)

            elif platform == "Bitbucket":
                if "Resource not found" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Bitbucket'):
                            lines[i] = 'Bitbucket ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)

            elif platform == "Patreon":
                if "This page could not be found" in page_source:
                    pass
                else:
                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'r') as file:
                        lines = file.readlines()
                    
                    for i, line in enumerate(lines):
                        if line.startswith('Patreon'):
                            lines[i] = 'Patreon ' + url + '\n'
                            break

                    with open('C:/xampp/htdocs/abyssseek/files/usernamefinder/src/sites.txt', 'w') as file:
                        file.writelines(lines)

                driver.quit()
                os.remove(loaderTrigger)

            else:
                driver.quit()
                os.remove(loaderTrigger)
            

        except Exception as e:
            pass

    driver.quit()
    os.remove(loaderTrigger)

def disable_event():
    pass

def start():
    global entry
    global label
    global labels
    global search_button
    global root
    global platform
    global platforms

    search_username()

start()
