<?php

namespace PurpleSpider\AssetDeleteIfUnusedExtension;

use SilverStripe\Dev\Debug;
use SilverStripe\Assets\Folder;
use SilverStripe\Security\Member;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Versioned\ChangeSetItem;
use SilverStripe\Assets\Shortcodes\FileLink;

class AssetDeleteIfUnusedExtension extends DataExtension
{
    public function deleteIfUnused()
    {
        $excluded = [
          ChangeSetItem::class,
          Member::class,
          FileLink::class,
          Folder::class,
        ];

        $relatedItems = $this->owner->findAllRelatedData($excluded);

        if(!$relatedItems->Count() || ($relatedItems->Count() == 1 && $relatedItems->First()->ClassName == Folder::class) ) {
            $this->owner->deleteFile();
            $this->owner->destroy();
            $this->owner->delete();
        }
    }
}