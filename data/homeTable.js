db.matches.aggregate([ 
    { $group: 
        {
            _id:"$HomeTeam", 
            matches: { $sum: 1 }, 
            points: { $sum: 
                {
                    $cond: [
                        { $gt:["$FTHG","$FTAG"] },
                            3,
                            { $cond:
                                [{$eq:["$FTHG","$FTAG"]},1,0]
                            }
                    ] 
                } 
            },
            v: { $sum:
                {
                    $cond: [
                        { $gt:["$FTHG","$FTAG"] }, 1 , 0
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
                        { $gt:["$FTHG","$FTAG"] }, 0 , 1
                    ]
                }
            },
           gf: { $sum : "$FTHG" },
           ga: { $sum : "$FTAG" }

        } 
    },
    { $sort : { points: -1 } }, 
    { $out: "homeTable" }
]);




