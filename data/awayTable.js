db.matches.aggregate([ 
    { $group: 
        {
            _id:"$AwayTeam", 
            matches: { $sum: 1 }, 
            points: { $sum: 
                {
                    $cond: [
                        { $gt:["$FTAG","$FTHG"] },
                            3,
                            { $cond:
                                [{$eq:["$FTAG","$FTHG"]},1,0]
                            }
                    ] 
                } 
            },
            v: { $sum:
                {
                    $cond: [
                        { $gt:["$FTAG","$FTHG"] }, 1 , 0
                    ]
                }
            },
            e: { $sum:
                {
                    $cond: [
                        { $eq:["$FTHG","$FTAG"] }, 1 , 0
                    ]
                }
            },
            d: { $sum:
                {
                    $cond: [
                        { $gt:["$FTAG","$FTHG"] }, 0 , 1
                    ]
                }
            },
           gf: { $sum : "$FTAG" },
           ga: { $sum : "$FTHG" }
        } 
    }, 
    { $sort : { points: -1 } }, 
    { $out: "awayTable" }
]);




