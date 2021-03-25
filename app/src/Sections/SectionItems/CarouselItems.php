<?php

namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\Image;
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\HiddenField;
    use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
    use SilverStripe\Forms\ReadonlyField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\Forms\TreeDropdownField;
    use SilverStripe\ORM\DataObject;

    class CarouselItems extends DataObject
    {
        private static $default_sort = 'Sort';

        private static $db = [
            'Name'     => 'Varchar',
            'Content'  => 'HTMLText',
            'Archived' => 'Boolean',
            'Sort'     => 'Int',
        ];

        private static $has_one = [
            'Parent' => Carousel::class,
            'Page'   => SiteTree::class,
            'Image'  => Image::class,
        ];

        private static $owns = [
            'Image'
        ];

        private static $summary_fields = [
            'Name',
            'Status'
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields(); // TODO: Change the autogenerated stub
            $fields->removeByName('ParentID');
            $fields->addFieldToTab('Root.Main', ReadonlyField::create('ParentRO', 'Parent', $this->Parent()->Name));

            $fields->addFieldToTab('Root.Main', TextField::create('Name'));
            $fields->addFieldToTab('Root.Main', TreeDropdownField::create('PageID', 'Page link', SiteTree::class)
                ->setDescription('Select a page to populate content (Page should have an image and/or content otherwise use image upload and content field below)'));
            $fields->addFieldToTab('Root.Main', UploadField::create('Image')
                ->setFolderName('Carousel/Images')
                ->setDescription('Optional: Only upload file when selected page does not have an image'));
            $fields->addFieldToTab('Root.Main', HTMLEditorField::create('Content')
                ->setDescription('Optional: Only add text when selected page does not have a content'));
            $fields->addFieldToTab('Root.Main', CheckboxField::create('Archived'));
            $fields->addFieldToTab('Root.Main', HiddenField::create('Sort'));

            return $fields;
        }

        public function getStatus()
        {
            if($this->Archived == 1) return _t('GridField.Archived', 'Archived');
            return _t('GridField.Live', 'Live');
        }

        public function onBeforeWrite()
        {
            parent::onBeforeWrite();
            if($this->Page) {
                if (!$this->Name) {
                    $this->Name = $this->Page->Title;
                }
            }
        }
    }
}
