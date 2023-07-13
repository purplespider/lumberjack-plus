<?php

namespace PurpleSpider\LumberjackPlus;

use PhpParser\Node\Name;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\FieldList;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Lumberjack\Model\Lumberjack;

class LumberjackPlus extends Lumberjack
{

	/**
	 * This is responsible for adding the child pages tab and gridfield.
	 * CUSTOM: Customised to allow the GridField tab to appear first.
	 *
	 * @param FieldList $fields
	 */
	public function updateCMSFields(FieldList $fields)
	{
		$excluded = $this->owner->getExcludedSiteTreeClassNames();
		if (!empty($excluded)) {
			$pages = $this->getLumberjackPagesForGridfield($excluded);
			$gridField = new GridField(
				"ChildPages",
				$this->getLumberjackTitle(),
				$pages,
				$this->getLumberjackGridFieldConfig()
			);

			$tab = new Tab('ChildPages', $this->getLumberjackTitle(), $gridField);

			// BEGIN CUSTOMISATION

			if (method_exists($this->owner, 'getLumberjackTabPosition')) {
				if ($this->owner->getLumberjackTabPosition() == 'first') {
					$fields->insertBefore('Main', $tab);
				} elseif ($this->owner->getLumberjackTabPosition() == 'first-unless-new') {
					if (SiteTree::get()->filter('ParentID', $this->owner->ID)->count()) {
						$fields->insertBefore('Main', $tab);
					} else {
						$fields->insertAfter('Main', $tab);
					}
				} else {
					$fields->insertAfter('Main', $tab);
				}
			} else {
				$fields->insertAfter('Main', $tab);
			}

			// END CUSTOMISATION

		}
	}


	// The following funcations are Copyright 2020 Evans Hunt Group

	// this will use $summary_fields and $default_sort of the provided class
	public function getLumberjackPagesForGridField($excluded = [])
	{
		$childClasses = $this->getChildClassesOtherThanSiteTree();

		if (count($childClasses) === 1) {
			$className = $childClasses[0];
			return $className::get()->filter(
				[
					'ParentID' => $this->owner->ID,
					'ClassName' => $excluded,
				]
			);
		}
		return parent::getLumberjackPagesForGridField($excluded);
	}

	// this will change the tab title
	public function getLumberJackTitle()
	{
		$childClasses = $this->getChildClassesOtherThanSiteTree();

		if (count($childClasses) === 1) {
			return Config::inst()->get($childClasses[0], 'plural_name');
		}
		return parent::getLumberjackTitle();
	}

	private function getChildClassesOtherThanSiteTree()
	{
		$childClasses = Config::inst()->get(get_class($this->owner), 'allowed_children');
		return array_values(array_filter($childClasses, function ($className) {
			return $className !== SiteTree::class;
		}));
	}
}
