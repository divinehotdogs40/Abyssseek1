const puppeteer = require('puppeteer');

async function scrape(url) {
    try {
        const browser = await puppeteer.launch();
        const page = await browser.newPage();
        await page.goto(url, { waitUntil: 'domcontentloaded' });

        const data = await page.evaluate(() => {
            const links = Array.from(document.querySelectorAll('a')).map(link => ({
                tag: 'a',
                href: link.href.trim(),
                text: link.textContent.trim()
            }));

            const paragraphs = Array.from(document.querySelectorAll('p')).map(para => ({
                tag: 'p',
                text: para.textContent.trim()
            }));

            const images = Array.from(document.querySelectorAll('img')).map(img => ({
                tag: 'img',
                src: img.src.trim(),
                alt: img.alt.trim()
            }));

            return { 
                success: true, 
                links,
                paragraphs,
                images
            };
        });

        await browser.close();
        return data;
    } catch (error) {
        throw new Error('Error during scraping:', error);
    }
}

const url = process.argv[2];

scrape(url)
    .then(data => console.log(JSON.stringify(data)))
    .catch(error => console.error(error));
