<?php
namespace be\bastelstu\max\wcf\otu;

if (file_exists(WCF_DIR.'lib/acp/page/OTUListPage.class.php')) {
	unlink(WCF_DIR.'lib/acp/page/OTUListPage.class.php');
}
