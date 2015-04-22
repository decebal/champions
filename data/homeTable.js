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
           gf: { $sum : "$FTHG" },
           ga: { $sum : "$FTAG" }

        } 
    },
    { $sort : { points: -1 } }, 
    { $out: "homeTeams" } 
]);




