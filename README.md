Elgg Google Apps Integration
==========

This plugin provides integtation with a Google Apps domain, with the following features:

* Share and embed Google Docs
* List domain Sites
* Google single sign-in

Notes
---
In order to embed a Google drive directly, you will need to allow insertion of iframes in CKEditor, ie in CKEditor config:

    extraAllowedContent: 'iframe[src,alt,width,height]'

This isn't ideal, as you'll need to also allow iframes through HTMLawed (which isn't recommended). 


The following views can be overwritten:

* googleapps/embedfile
* googleapps/embedfolder

I'd recommending making use of Elgg's [ECML plugin](https://github.com/elgg/ecml) in these custom views to generate/display embed code.



---

#### Google Apps plugin by Jeff Tilson for THINK Global School [http://www.thinkglobalschool.org](http://www.thinkglobalschool.org)