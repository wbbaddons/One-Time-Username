<?php

/**
 * @author Tim Duesterhus
 * @copyright 2013 Maximilian Mader
 * @license BSD 3-Clause License <http://opensource.org/licenses/BSD-3-Clause>
 */

use wcf\system\database\table\index\DatabaseTableIndex;
use wcf\system\database\table\PartialDatabaseTable;

// 1) Generate a blueprint to fill in the generated index names.

$blueprint = [
    PartialDatabaseTable::create('wcf1_user_otu_blacklist_entry')
        ->indices([
            DatabaseTableIndex::create('')
                ->columns([
                    'username',
                ]),
            DatabaseTableIndex::create('')
                ->columns([
                    'time',
                ]),
        ]),
];

// 2) Use this blueprint to recreate the index objects with ->generatedName() set to false.
// Simply dropping the indices with ->generatedName() set to true does not work, because it will
// also remove named indices as the fact that a name was generated does not persist to the database.

$data = [];
foreach ($blueprint as $blueprintTable) {
    $data[] = PartialDatabaseTable::create($blueprintTable->getName())
        ->indices(\array_map(static function ($index) {
            \assert($index instanceof DatabaseTableIndex);

            return DatabaseTableIndex::create($index->getName())
                ->columns($index->getColumns())
                ->type($index->getType())
                ->drop();
        }, $blueprintTable->getIndices()));
}

return $data;
