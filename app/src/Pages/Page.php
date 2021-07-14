<?php

namespace {

    use SilverStripe\AssetAdmin\Forms\UploadField;
    use SilverStripe\Assets\File;
    use SilverStripe\Assets\Image;
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
    use SilverStripe\Forms\TextField;
    use SilverStripe\ORM\PaginatedList;
    use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
    use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

    class Page extends SiteTree
    {
        private static $db = [
            'HeaderTheme' => 'Varchar',
            'EnableFullPage' => 'Boolean',
        ];

        private static $has_one = [
            'PageIcon' => File::class,
            'PageIconActive' => File::class,
            'MenuBackground' => Image::class,
            'PageBackground' => Image::class
        ];

        private static $owns = [
            'PageIcon',
            'PageIconActive',
            'MenuBackground',
            'PageBackground'
        ];

        private static $has_many = [
            'Sections' => Section::class
        ];

        public function getCMSFields()
        {
            $fields = parent::getCMSFields(); // TODO: Change the autogenerated stub
            $fields->removeByName(['Content']);

            $fields->addFieldToTab('Root.MenuIcon', UploadField::create('PageIcon', 'Menu icon')
                ->setFolderName('PageMenu/Icons'));
            $fields->addFieldToTab('Root.MenuIcon', UploadField::create('PageIconActive', 'Menu icon active')
                ->setFolderName('PageMenu/Icons'));
            $fields->addFieldToTab('Root.MenuIcon', $menubg = UploadField::create('MenuBackground', 'Menu background'));
                $menubg->setFolderName('PageMenu/Background');
                $menubg->setAllowedExtensions(['png','gif','jpeg','jpg']);


            $gridConfig = GridFieldConfig_RecordEditor::create(9999);
            if ($this->Sections()->Count()) {
                $gridConfig->addComponent(new GridFieldSortableRows('Sort'));
            }
            $gridConfig->addComponent(new GridFieldEditableColumns());
            $gridColumns = $gridConfig->getComponentByType(GridFieldEditableColumns::class);
            $gridColumns->setDisplayFields([
                'SectionWidth' => [
                    'title' => 'Section Width',
                    'callback' => function($record, $column, $grid) {
                        $fields = DropdownField::create($column, $column, SectionWidth::get()->filter('Archived', false)->map('Class','Name'));
                        return $fields;
                    }
                ],
                'Archived' => [
                    'title' => 'Archive',
                    'callback' => function($record, $column, $grid) {
                        return CheckboxField::create($column);
                    }
                ]
            ]);

            $gridField = GridField::create(
                'Sections',
                'Sections',
                $this->Sections(),
                $gridConfig
            );

            $fields->addFieldToTab('Root.Main', CheckboxField::create('EnableFullPage', 'Enable fullscreen scrolling layout')
                ->setDescription('See<a href="https://alvarotrigo.com/fullPage/" target="_blank" rel="noreferrer nofollow">&nbsp;fullPage.js&nbsp;page&nbsp;</a>for reference.'),'Metadata');

            $fields->addFieldToTab('Root.Settings', DropdownField::create('HeaderTheme', 'Header theme', array(
                'header-light' => 'Header light',
                'header-dark'  => 'Header dark'
            )));
            $fields->addFieldToTab('Root.Settings', $pagebg = UploadField::create('PageBackground', 'Page background'));
                $pagebg->setFolderName('Page/Background');
                $pagebg->setAllowedExtensions(['png','gif','jpeg','jpg']);

            $fields->addFieldToTab('Root.Main', $gridField, 'Metadata');

            return $fields;
        }

        public function getVisibleSections()
        {
            return $this->Sections()->filter('Archived', false)->sort('Sort');
        }
    }
}
