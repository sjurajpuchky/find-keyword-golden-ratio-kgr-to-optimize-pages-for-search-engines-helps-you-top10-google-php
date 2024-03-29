# Find keyword golden ratio (kgr) to optimize pages for search engines and other utilities for seo in php 
## my page TOP 10 on Google within 48 hours
## Make your page TOP 10 on google in 48 hours
### Quick tool which helps you make your page TOP 10 on Google in 48 hours
Main purpose of this library is to automate SEO routine for finding **KGR** **keyword golden ratio**.
This tool will help you simply on **long tail keyword** **make your page top 10 on google within 48 hours**.

# Tutorial

https://www.youtube.com/watch?v=KQsGHZSY64I

# Install

```
git clone https://github.com/sjurajpuchky/find-keyword-golden-ratio-kgr-to-optimize-pages-for-search-engines-helps-you-top10-google-php.git search-analytics-utilities
cd search-analytics-utilities
composer install
```

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

# Supported APIs
- Google ADS API with MMC account, developer token
- Collabim API


# Examples
In folder samples you can find some basic usage of library.

```php samples/kgr-csv.php ./samples/keywords.csv ./results.csv```

```php samples/kgr-collabim.php ./samples/keywords.csv ./results.csv```

# How to use it?
As you can see in samples you have to first make instance of Engine then pass Engine to Analyzer tool, which has an interface to call operation with results.
Instance of Engine requires instance of Cache Driver to store results in the cache.

# Tutorial
https://www.youtube.com/watch?v=KQsGHZSY64I

# Where get long tail keywords?
in application is also implemented google-ads API keyword planner, for which you must have MMC account with activated developer token. Or export results from keyword planner in ADS to CSV, (keyword;volume) format.
If you do not have google-ads-api developer token, you can use collabim (https://www.collabim.com/?promoCode=mRfeciXH1V)
Wonderful is also keyword-researcher (http://babatumise.clevergiz.hop.clickbank.net?cgpage=keyword-researcher) and Answer the public (https://answerthepublic.com/)

# License
GPL-2.0-only for non-commercial usage. What that means? If you plan to use it for your own projects it's free of charge, but if you like to use in your commercial application is payed with subscrption fee and has support. Otherwise is without support.

# For COMMERCIAL use ask us
For commercial use, contact us on info@baba.bj, to request permission for access to privete repository, where is all needed to do proffesional KGR analysis for your clients. Subbscriptions payment is 100€ per year without limits. Commercial version of application is with support and contains lot of new functions like
  - connection to varius APIs
  - multiple search engines support
  - API for our Keyword suggestion API
  - reporting in PDF with HTML Twig templates
  - extended tools
  - and others

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

1.3.6 - tested google ads api suggestions with kgr + samples

1.4.0 - collabim api implemented

# Copyright
&copy; 2021 BABA Tumise s.r.o.

# Thanks to
Created with help of Dalibor Jaroš - CEO of Collabim and their webinar about KGR SEO method (https://collabim.cz/kgr?promoCode=mRfeciXH1V),

you can also check SEO trénink (https://collabim.cz/seo-trenink?promoCode=mRfeciXH1V) which is one of the actually most honored SEO course on the Czech market.

# Special discount
for Collabim services and products you can use discount coupon AFF-DIS-30, coupon is possible to use only with links mentioned above.

# Services
we provide kgr analysis service, for fixed fee, contact me info@baba.bj
