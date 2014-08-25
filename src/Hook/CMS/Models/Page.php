<?php namespace Hook\CMS\Models;

use Hook\Model\Collection;

class Page extends Collection {
    protected $table = 'cms_pages';

    public function widgets() {
        return $this->hasMany('Hook\\CMS\\Models\\PageWidget');
    }
}
