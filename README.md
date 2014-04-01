# Live Demo

[We Summarize: 83.84.62.217] (http://83.84.62.217/)

**Test User Credentials:**  
Username: karensg  
Password: asdasd

# Installation instructions

1. Install an Apache/PHP/MySQL environment (Tested with Wamp Server 2.4. Apache : 2.4.4 MySQL : 5.6.12 PHP : 5.4.16 )
2. Enable mod-rewrite
3. Clone the repository in your www folder with the following command:  
`git clone https://github.com/yetti4/crowd-summary.git`
4. Install ElasticSearch  
`http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/setup-service-win.html`
5. Rename database.sample.php in /app/Config/ to database.php and provide the right mySQL credentials
6. Import crowdsum.sql into mySQL database 'crowdsum'
7. Visit the website on your server and login as user 'bouke' with password 'bouke' 
8. Go to yourwebsite/documents/reIndex to index test data in elastic search
9. Enjoy using the crowdsummary website
