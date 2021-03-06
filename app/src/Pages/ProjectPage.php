<?php

namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\Image;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
    use SilverStripe\Forms\ListboxField;
    use SilverStripe\ORM\ArrayList;
    use SilverStripe\TagField\StringTagField;
    use SilverStripe\TagField\TagField;
    use SilverStripe\View\ArrayData;

    class ProjectPage extends Page
    {
        private static $icon_class = 'font-icon-block-content';

        private static $db = [
            'Year'            => 'Varchar(255)',
            'AboutTheProject' => 'HTMLText',
            'PreContent'      => 'HTMLText',
            'Authors'         => 'Text',
        ];

        private static $has_one = [
            'FeaturedImage' => Image::class,
            'PageBanner'    => Image::class,
        ];

        private static $has_many = [

        ];

        private static $many_many = [
            'RelatedProjects' => ProjectPage::class,
            'Categories'      => ProjectCategory::class,
        ];

        private static $owns = [
            'FeaturedImage',
            'PageBanner',
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields(); // TODO: Change the autogenerated stub
            $fields->addFieldToTab('Root.Main', UploadField::create('FeaturedImage')
                ->setFolderName('ProjectPage/Featured_Images'),'Sections');
            $fields->addFieldToTab('Root.ExtraContent', UploadField::create('PageBanner')
                ->setFolderName('ProjectPage/Banner_Images'), 'Sections');
            $fields->addFieldToTab('Root.ExtraContent', HTMLEditorField::create('AboutTheProject', 'About the project'));
            $fields->addFieldToTab('Root.ExtraContent', HTMLEditorField::create('PreContent', 'Pre content'));
            $fields->addFieldToTab('Root.Main', StringTagField::create('Authors', 'Author/s',
                StaffPage::get(), explode(',', $this->Authors))->setCanCreate(true), 'FeaturedImage');
            $fields->addFieldToTab('Root.Main', TagField::create('Categories', 'Categories',
                ProjectCategory::get(), $this->Categories())->setCanCreate(true),'FeaturedImage');
            $fields->addFieldToTab('Root.Main', ListboxField::create('RelatedProjects', 'Related projects',
                ProjectPage::get()->filter('Title:not', $this->owner->Title)->map("ID", "Title")),'FeaturedImage');
            $fields->addFieldToTab('Root.Main', DropdownField::create('Year', 'Year', $this->getYearFilter()->map('Name', 'Name')),'FeaturedImage');

            return $fields;
        }

        public function getYearFilter()
        {
            $yearLists = $this->Parent()->FilterYear;
            $years    = explode(",",$yearLists);

            $output = new ArrayList();
            foreach($years as $year) {
                $output->push(
                    new ArrayData(array('Name' => $year))
                );
            }
            return $output;
        }

        public function getReadableAuthors()
        {
            $authorLists = $this->Authors;
            $authors = explode(',', $authorLists);

            $output = new ArrayList();
            foreach ($authors as $author) {
                $output->push(
                    new ArrayData(array('Name' => $author))
                );
            }
            return $output;
        }
    }
}
