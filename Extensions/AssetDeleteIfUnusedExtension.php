<?php

namespace PurpleSpider\AssetDeleteIfUnusedExtension;

use SilverStripe\Assets\Folder;
use SilverStripe\Assets\Shortcodes\FileLink;
use SilverStripe\Core\Extension;
use SilverStripe\Security\Member;
use SilverStripe\Versioned\ChangeSetItem;

class AssetDeleteIfUnusedExtension extends Extension
{

    public function deleteIfUnused(): void
    {
        $excluded = [
            ChangeSetItem::class,
            Member::class,
            FileLink::class,
            Folder::class,
        ];

        $relatedItems = $this->getOwner()->findAllRelatedData($excluded);

        if (!$relatedItems->Count() || ($relatedItems->Count() == 1 && $relatedItems->First()->ClassName == Folder::class)) {
            $this->getOwner()->deleteFile();
            $this->getOwner()->destroy();
            $this->getOwner()->delete();
        }
    }
}