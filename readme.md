# Delete Asset If Unused Extension for Silverstripe

This Silverstripe module provides a new `deleteIfUnused()` method to `SilverStripe\Assets\File` objects, which when called will delete the File, but *only* if it isn't being used anywhere, e.g. by another `Page` or `DataObject`.

# When would I want to use this?

Say you have a homepage slideshow, and each slide can have an image. 

If a CMS user deletes a slide, it should also ideally delete any image that had been uploaded for that slide (to avoid orphaned images wasting disk space), but there is a risk that this image has been used elsewhere, e.g. perhaps a CMS user added the same image to a page via the WYSIWYG editor, or they selected the same image for another slide, without re-uploading it. 

This module allows you to tell the image to be deleted (e.g. from your slide's `onAfterDelete` method), but *only* if it isn't associated with any other Pages or DataObjects. 

If it is associted with another object, it won't get deleted, unless, of course, you go and delete the other object, at which point it *will* delete the image as it will no longer be associated with anything.

## Installation

1. Install module via composer:
````
composer require purplespider/silverstripe-asset-delete-if-unused-extension "^2"
````
For SilverStripe 4 or 5 use version 1 of this module.


2. Perform a `dev/build?flush=1`: 

3. Make use of the new `deleteIfUnused()` method in your code:
e.g. `SlideshowSlide.php`:
````
protected function onAfterDelete()
{
    $this->MyImage()->deleteIfUnused();

    parent::onAfterDelete();
}
````

## How does it work out if an asset is/isn't being used?

It uses the existing `findAllRelatedData()` method, which is what populates the **Used on** tab in the Files section of the CMS.

It also excludes the same classes that the **Used on** tab does from counting the asset as "used", e.g. `ChangeSetItem`, `Member` `FileLink`, `Folder`.

## Anything else I should be aware of?

Always keep backups! I provide no guarantee that this module won't delete used assets, it's up to you to test it fully in your desired scenareo.

Warning, if you use this on a site that has been upgraded from Silverstripe 3, it is likely that images in WYSIWIG fields don't have the `ImageID` attribute on the `img` tag, so it won't detect that the image is being used on that page, potentially resulting in a `used` image being deleted.

