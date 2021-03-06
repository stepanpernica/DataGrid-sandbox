<?php

namespace App\Presenters;

use Nette,
    App\Model,
    \Mesour\DataGrid\Grid,
    \Mesour\DataGrid\NetteDbDataSource,
    \Mesour\DataGrid\Components\Link,
    \Mesour\DataGrid\DibiDataSource;


/**
 * Basic presenter.
 */
class FullPresenter extends BasePresenter {

 	protected function createComponentFullDataGrid($name) {
		$source = new NetteDbDataSource($this->demo_model->getUserSelection());

		$grid = new Grid($this, $name);

		$table_id = 'user_id';

		$source->setPrimaryKey($table_id);

		$grid->setDataSource($source);

		$grid->enablePager(5);

		$grid->enableEditableCells();

		$grid->enableFilter();

		$grid->enableExport(__DIR__ . '/../../temp/cache');

		$grid->enableSorting();

		$selection = $grid->enableRowSelection();

		$selection->addLink('Active')
		    ->onCall[] = $this->activeSelected;

		$selection->addLink('Unactive')
		    ->setAjax(FALSE)
		    ->onCall[] = $this->unactiveSelected;

		$selection->addLink('Delete')
		    ->setConfirm('Really delete all selected users?')
		    ->onCall[] = $this->deleteSelected;

		$grid->onEditCell[] = $this->editCell;

		$grid->onSort[] = $this->editSort;

		$status_column = $grid->addStatus('action', 'S');

		$status_column->addButton()
		    ->setStatus('0') //! show if status == 0
		    ->setType('btn-danger')
		    ->setClassName('ajax')
		    ->setIcon('glyphicon-ban-circle')
		    ->setTitle('Set as active (unactive)')
		    ->addAttribute('href', new Link(array(
			Link::HREF => 'changeStatusUser!',
			Link::PARAMS => array(
			    'id' => '{' . $table_id . '}',
			    'status' => 1
			)
		    )));

		$status_column->addButton()
		    ->setStatus('1') //! show if status == 1
		    ->setType('btn-success')
		    ->setClassName('ajax')
		    ->setIcon('glyphicon-ok-circle')
		    ->setTitle('Set as unactive (active)')
		    ->addAttribute('href', new Link(array(
			Link::HREF => 'changeStatusUser!',
			Link::PARAMS => array(
			    'id' => '{' . $table_id . '}',
			    'status' => 0
			)
		    )));

		$grid->addImage('avatar')
		    ->setMaxHeight(80)
		    ->setMaxWidth(80);

		$grid->addText('name', 'Name');

		$grid->addDate('last_login', 'Last login')
		    ->setFormat('j.n.Y');

		$grid->addNumber('amount', 'Amount');

		$container = $grid->addContainer('name', 'Name');

		$container->addText('name')
		    ->addAttribute('class', 'bbb')
		    ->addAttribute('class', 'ccc', TRUE);

		$container->addText('surname');

		$container2 = $grid->addContainer('name', 'Actions')
		    ->setOrdering(FALSE);

		$actions = $container2->addActions();

		$actions->addDropDown()
		    ->setType('btn-danger')
		    ->addGroup('DropDown header')
		    ->addLink('DataGrid:editUser', 'Test link', array(
			'id' => '{' . $table_id . '}'
		    ))
		    ->addLink('DataGrid:editUser', 'Test link 2', array(
			'id' => '{' . $table_id . '}'
		    ))
		    ->addSeparator()
		    ->addGroup('DropDown header 2')
		    ->addLink('DataGrid:editUser', 'Test link 3', array(
			'id' => '{' . $table_id . '}'
		    ));

		$actions->addButton()
		    ->setType('btn-primary')
		    ->setIcon('glyphicon-pencil')
		    ->setTitle('Edit')
		    ->addAttribute('href', new Link(array(
			Link::HREF => 'DataGrid:editUser',
			Link::PARAMS => array(
			    'id' => '{' . $table_id . '}'
			)
		    )));

		$actions->addButton()
		    ->setType('btn-danger')
		    ->setIcon('glyphicon-trash')
		    ->setConfirm('Realy want to delete user?')
		    ->setTitle('Delete')
		    ->addAttribute('href', new Link(array(
			Link::HREF => 'deleteUser!',
			Link::PARAMS => array(
			    'id' => '{' . $table_id . '}'
			)
		    )));

		return $grid;
	}

}
