<?php

/**
 * @author Tim Duesterhus
 * @copyright 2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 */

use wcf\system\database\table\column\IntDatabaseTableColumn;
use wcf\system\database\table\column\NotNullInt10DatabaseTableColumn;
use wcf\system\database\table\column\NotNullVarchar191DatabaseTableColumn;
use wcf\system\database\table\column\ObjectIdDatabaseTableColumn;
use wcf\system\database\table\DatabaseTable;
use wcf\system\database\table\index\DatabaseTableForeignKey;
use wcf\system\database\table\index\DatabaseTableIndex;
use wcf\system\database\table\index\DatabaseTablePrimaryIndex;

return [
    DatabaseTable::create('wcf1_user_otu_blacklist_entry')
        ->columns([
            ObjectIdDatabaseTableColumn::create('entryID'),
            NotNullVarchar191DatabaseTableColumn::create('username'),
            NotNullInt10DatabaseTableColumn::create('time'),
            IntDatabaseTableColumn::create('userID')
                ->length(10)
                ->defaultValue(null),
        ])
        ->indices([
            DatabaseTablePrimaryIndex::create()
                ->columns([
                    'entryID',
                ]),
            DatabaseTableIndex::create('username')
                ->columns([
                    'username',
                ]),
            DatabaseTableIndex::create('time')
                ->columns([
                    'time',
                ]),
        ])
        ->foreignKeys([
            DatabaseTableForeignKey::create()
                ->columns([
                    'userID',
                ])
                ->referencedTable('wcf1_user')
                ->referencedColumns([
                    'userID',
                ])
                ->onDelete('SET NULL'),
        ]),
];
