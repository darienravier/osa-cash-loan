<?php
class LoanRequestsMapper extends \DB\Sql\Mapper {
	public function __construct(\DB\SQL $db) {
		parent::__construct($db,'loanrequests');
	}
}
