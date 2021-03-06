<?php

class page_starchart_disambig extends holonet_page {
	
	public function buildPage() {

		$this->pageBuilt = true;

		$trail = $this->getTrailingElements();
		$search = $trail[0];
		$sites = array_pop($trail);
		$planets = array_pop($trail);
		$systems = array_pop($trail);

		if (	 !$systems instanceof bhg_core_list
				|| !$planets instanceof bhg_core_list
				|| !$sites instanceof bhg_core_list) {

			$this->setTitle('Error');
			$this->addBodyContent('This is not the page you are looking for. Move along.');
			return;

		}

		$this->setTitle('Multiple Records Found');

		$parts = array();

		if ($systems->count() > 0)
			$parts[] = number_format($systems->count())
				.' system match'
				.($systems->count() != 1 ? 'es' : '');

		if ($planets->count() > 0)
			$parts[] = number_format($planets->count())
				.' planet match'
				.($planets->count() != 1 ? 'es' : '');

		if ($sites->count() > 0)
			$parts[] = number_format($sites->count())
				.' site match'
				.($sites->count() != 1 ? 'es' : '');

		$string = '<p>Your search for "'
			.htmlspecialchars($search)
			.'" returned ';

		for ($i = 0, $count = count($parts); $i < $count; $i++) {

			if ($i == 0) {

				$join = ' ';

			} elseif ($i == ($count - 1)) {

				$join = ' and ';

			} else {

				$join = ', ';

			}

			$string .= $join.$parts[$i];

		}

		$string .= '</p>';
		
		$this->addBodyContent($string);

		$bar = new holonet_tab_bar;

		if ($systems->count() > 0) {

			$tab = new holonet_tab('systems', 'Systems');

			$tab->addContent(holonet::buildList($systems));

			$bar->addTab($tab);

		}

		if ($planets->count() > 0) {

			$tab = new holonet_tab('planets', 'Planets');

			$tab->addContent(holonet::buildList($planets));

			$bar->addTab($tab);

		}

		if ($sites->count() > 0) {

			$tab = new holonet_tab('sites', 'Sites');

			$tab->addContent(holonet::buildList($sites));

			$bar->addTab($tab);

		}

		$this->addBodyContent($bar);

		$this->addSideMenu($GLOBALS['holonet']->starchart->getSideMenu());

	}

}

?>
