# Ghost Hit Counter
Add page view counter for Ghost blog

Live demo: [My blog's popular posts](https://prinzeugen.net/popular-posts)

### Installation

1. Configure your database information in config.php
2. Write a JavaScript for your blog
3. That's it!

### APIs

All request method is GET.

Get post views of one slug:
```
index.php?action=get&slug={{foo}}
```

Add an post view of one slug:
```
index.php?action=add&slug={{bar}}
```

Get popular slugs:
```
index.php?action=order&limit={{limit}}
```

### Migrations

Migrate from WP-PostViews to Ghost Hit Counter

https://gist.github.com/printempw/3a72cf916a6689c7a665
