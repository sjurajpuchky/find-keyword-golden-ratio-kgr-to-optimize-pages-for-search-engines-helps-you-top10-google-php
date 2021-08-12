# search-analytics-utilities
Main purpose of this library is to automate SEO routine for finding KGR.

# Install

git clone https://github.com/sjurajpuchky/search-analytics-utilities.git or download from https://github.com/sjurajpuchky/search-analytics-utilities and unzip
cd search-analytics-utilities
composer install

For google-ads-api suggestions you need activated mmc account and developer key, once you can't have it you can use sample kgr-csv.php with keywords and volumes in csv, which you can obtain from google-ads onr another seo tool as well.
Feel free to look into samples folder and checkout 

### Free keyword golden ratio tool.

For that is needed to find out number of search results on Google and monthly search volume for each keyword idea.

---
# Dependencies
Our library depends on the package "baba/object-cache", which is used for prevent banning on Google.

# Simple library to find out 
- long tail keywords by Google Ads API (MMC account + API needed)
- KGR (keyword golden ratio), you can use against your ideas
- if you do not have MMC account you can export keywords from keyword planning tool on Google Ads or any other list and use CSV to find out your KGR

# Supported engines
- Google

# Examples
In folder samples you can find some basic usage of library.

`php samples/kgr-csv.php ./samples/keywords.csv ./results.csv ./future.csv`

# How to use it?
As you can see in samples you have to first make instance of Engine then pass Engine to Analyzer tool, which has an interface to call operation with results.
Instance of Engine requires instance of Cache Driver to store results in the cache.

# Tutorial
https://www.youtube.com/watch?v=KQsGHZSY64I

# Where get long tail keywords?
I am suing Semrush https://www.semrush.com (Magick Keyword tool) which I recommend buying, but in application is also implemented google-ads API keyword planner, for which you must have MMC account with activated developer token

# License
GPL-2.0-only

# Authors
Juraj Puchký - BABA Tumise s.r.o. <info@baba.bj>

https://www.seoihned.cz - SEO optilamizace

https://www.baba.bj - Tvorba webových stránek

https://www.webtrace.cz - Tvorba portálů a ecommerce b2b/b2c (eshopů) na zakázku

# Log
1.0.0 - first release

1.0.1 - some fixes regarding kgr specification

1.0.2 - future keywords

1.0.3 - better statistics in sample

# Copyright
&copy; 2021 BABA Tumise s.r.o.

# Thanks to
Created with help of Dalibor Jaroš CEO Collabimu and theirs free webinar about kgr https://collabim.teachable.com/p/umisteni-v-top10-na-google-do-48-hodin, you can also check https://collabim.teachable.com/p/seo-trenink-od-collabimu which is best seo webinar ever