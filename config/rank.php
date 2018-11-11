<?php

return [
    'ranking'=>[
        'v_o'=>[
            'des'=>'Visited outlet %',
            'marks'=>20,
            'required_mark'=>100,
            'remark'=>'percentage'
        ],
        'c_p'=>[
            'des'=>'Call Productivity %',
            'marks'=>30,
            'required_mark'=>85,
            'remark'=>'percentage'
        ],
        'bcp'=>[
            'des'=>'Brand Call Product',
            'marks'=>10,
            'required_mark'=>6,
            'remark'=>'number'
        ],
        'p_v'=>[
            'des'=>'Portfolio Volume',
            'marks'=>10,
            'required_mark'=>5,
            'remark'=>'number'
        ],
        'v_p_c'=>[
            'des'=>'Value Per Call',
            'marks'=>10,
            'required_mark'=>3000,
            'remark'=>'number'
        ],
        'b_c'=>[
            'des'=>'Bounce Call %',
            'marks'=>20,
            'required_mark'=>100,
            'remark'=>'percentage'
        ]
    ],
    'grade'=>[
        'outstanding'=>[
            'des'=>'Outstanding',
            'lower'=>80,
            'upper'=>100,
            'color'=>'59E759'
        ],
        'excelent'=>[
            'des'=>'Excelent',
            'lower'=>70,
            'upper'=>79,
            'color'=>'009900'
        ],
        'good'=>[
            'des'=>'Good',
            'lower'=>60,
            'upper'=>69,
            'color'=>'FFFF00'
        ],
        'average'=>[
            'des'=>'Average',
            'lower'=>50,
            'upper'=>59,
            'color'=>'FF9900'
        ],
        'poor'=>[
            'des'=>'Poor',
            'lower'=>0,
            'upper'=>49,
            'color'=>'FF0000'
        ]
    ]
];