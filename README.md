
benchmark time: 60s

Write performance

|                           | 1     | 5      | 10 | 25     | 50     |
|---------------------------|-------|--------|----|--------|--------|
| Redis without persistence | 42168 | 131728 |  103751  | 150035 | 149627 |
| Redis with default RDB    | 41893 | 130181 |    | 145674 | 149960 |
| Redis with AOF            | 35776 | 106682 |    | 136990 | 140987 |
| Redis RDB + AOF           | 36530 | 107573 |    | 136246 | 140552 |


Read performance

|                           | 1     | 5      | 25     | 50     |
|---------------------------|-------|--------|--------|--------|
| Redis without persistence | 43343 | 129749 | 149118 | 151255 |
| Redis with default RDB    | 43439 | 131322 | 147577 | 148736 |
| Redis with AOF            | 36892 | 104103 | 135208 | 138668 |
| Redis RDB + AOF           | 36631 | 107574 | 136622 | 141602 |
