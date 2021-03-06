<?php

namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\Image;
    use SilverStripe\Forms\DateField;
    use SilverStripe\Forms\ListboxField;
    use SilverStripe\Forms\TextareaField;
    use SilverStripe\Forms\TextField;
    use SilverStripe\ORM\ArrayList;
    use SilverStripe\View\ArrayData;

    class QISessionPage extends Page
    {
        private static $icon_class = 'font-icon-book-open';

        private static $db = [
            'Date'           => 'Date',
            'Time'           => 'Varchar',
            'Location'       => 'Text',
            'ContentSummary' => 'Text'
        ];

        private static $has_one = [
            'FeaturedImage' => Image::class,
        ];

        private static $many_many = [
            'Authors'       => StaffPage::class,
            'Categories'    => QISessionCategory::class,
            'SubCategories' => QISessionSubCategory::class
        ];

        private static $owns = [
            'FeaturedImage'
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields(); // TODO: Change the autogenerated stub
            $fields->addFieldToTab('Root.Main', UploadField::create('FeaturedImage')
                ->setFolderName('QualityImprovement/Sessions/'.$this->owner->Title.'/Images'), 'Sections');
            $fields->addFieldToTab('Root.Main', ListboxField::create('Authors', 'Author/s',
                StaffPage::get()->map("ID", "Title")), 'Sections');
            $fields->addFieldToTab('Root.Main', ListboxField::create('Categories', 'Categories',
                QISessionCategory::get()->filter('Archived', false)->map("ID", 'Title')),'FeaturedImage');
            $fields->addFieldToTab('Root.Main', DateField::create('Date'), 'Sections');
            $fields->addFieldToTab('Root.Main', TextField::create('Time')
                ->setDescription('e.g. 9:00 am - 10:00 pm'), 'Sections');
            $fields->addFieldToTab('Root.Main', TextareaField::create('Location'), 'Sections');
            $fields->addFieldToTab('Root.Main', TextareaField::create('ContentSummary'), 'Sections');

            return $fields;
        }

        public function getYearFilter() {
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
    }
}
