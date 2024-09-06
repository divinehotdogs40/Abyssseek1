document.addEventListener('DOMContentLoaded', function() {
    const historyButton = document.getElementById('historyButton');

    historyButton.addEventListener('click', function() {
        fetch('get_history.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let historyTableHTML = `
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>WEBSITE SCRAPE</th>
                                    <th>TIME SCRAPE</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    data.history.forEach(entry => {
                        historyTableHTML += `
                            <tr data-content="${encodeURIComponent(entry.content)}">
                                <td>${entry.web_address}</td>
                                <td>${new Date(entry.created_at).toLocaleString()}</td>
                            </tr>
                        `;
                    });

                    historyTableHTML += `
                            </tbody>
                        </table>
                    `;

                    const iframeContent = `
                        <html>
                        <head>
                            <link rel="stylesheet" type="text/css" href="styles.css">
                        </head>
                        <body>
                            <div class="content">
                                <h2>list of Scraped Website</h2>
                                ${historyTableHTML}
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const rows = document.querySelectorAll('tr[data-content]');
                                    rows.forEach(row => {
                                        row.addEventListener('click', function() {
                                            const content = decodeURIComponent(row.getAttribute('data-content'));
                                            window.parent.document.getElementById('resultFrame').srcdoc = content;
                                        });
                                    });
                                });
                            </script>
                        </body>
                        </html>
                    `;

                    document.getElementById('resultFrame').srcdoc = iframeContent;
                    document.getElementById('resultFrame').focus();
                } else {
                    console.error('Failed to fetch history:', data.error);
                }
            })
            .catch(error => console.error('Error fetching history:', error));
    });

    document.addEventListener('click', function(event) {
        var link = event.target.closest('a');
        if (link && link.closest('.gsc-webResult')) {
            event.preventDefault();

            var url = getCleanUrl(link.href);

            openInNewTab(url);

            fetch('scrape.php?url=' + encodeURIComponent(url))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const { links, paragraphs, images } = data;
                        const iframeContent = `
                            <html>
                            <head>
                                <link rel="stylesheet" type="text/css" href="styles.css">
                            </head>
                            <body>
                                <div class="content">
                                    <h2>Scraped Content from ${url}</h2> <!-- Display URL here -->
                                    <div class="section">
                                        <h3>Links</h3>
                                        <table class="scraped-table">
                                            <thead>
                                                <tr>
                                                    <th>Text</th>
                                                    <th>URL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${links.map(link => `
                                                    <tr>
                                                        <td>${link.text}</td>
                                                        <td><a href="${link.href}" target="_blank">${link.href}</a></td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="section">
                                        <h3>Paragraphs</h3>
                                        <table class="scraped-table">
                                            <thead>
                                                <tr>
                                                    <th>Text</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${paragraphs.map(para => `
                                                    <tr>
                                                        <td>${para.text}</td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="section">
                                        <h3>Images</h3>
                                        <table class="scraped-table">
                                            <thead>
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Alt Text</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${images.map(img => `
                                                    <tr>
                                                        <td><img src="${img.src}" alt="${img.alt}"></td>
                                                        <td>${img.alt}</td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </body>
                            </html>
                        `;
                        document.getElementById('resultFrame').srcdoc = iframeContent;

                        fetch('save_iframe.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ 
                                web_address: url, 
                                content: iframeContent 
                            })
                        }).then(response => response.json())
                          .then(data => {
                              if (data.success) {
                                  console.log('Iframe content saved successfully.');
                              } else {
                                  console.error('Failed to save iframe content:', data.error);
                              }
                          }).catch(error => console.error('Error saving iframe content:', error));
                    } else {
                        console.error('Scraping failed:', data.error);
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }
    });
});

function openInNewTab(url) {
    var win = window.open(url, '_blank', 'noopener,noreferrer,width=800,height=600,left=800,top=100');
    if (win) {
        win.focus();
    }
}

function getCleanUrl(url) {
    var match = url.match(/q=([^&]+)/);
    if (match && match[1]) {
        return decodeURIComponent(match[1]);
    }
    return url;
}
