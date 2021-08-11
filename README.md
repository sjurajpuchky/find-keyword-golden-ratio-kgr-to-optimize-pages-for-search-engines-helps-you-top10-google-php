# search-analytics-utilities
Main purpose of this library is to automate SEO routine for finding KGR.

# Install

git clone https://github.com/sjurajpuchky/search-analytics-utilities.git or download from https://github.com/sjurajpuchky/search-analytics-utilities and unzip
cd search-analytics-utilities
composer install

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

`php samples/kgr-csv.php ./samples/keywords.csv`

# How to use it?
As you can see in samples you have to first make instance of Engine then pass Engine to Analyzer tool, which has an interface to call operation with results.
Instance of Engine requires instance of Cache Driver to store results in the cache.

# License
GPL-2.0-only

# Authors
Juraj Puchký - BABA Tumise s.r.o. <info@baba.bj>

https://www.baba.bj

# Log
1.0.0 - first release

1.0.1 - some fixes regarding kgr specification

# Copyright
&copy; 2021 BABA Tumise s.r.o.