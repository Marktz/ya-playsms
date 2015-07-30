# Introduction #

Big changes to Autoreplies: gui changes, variables, ability to alias autoreplies scenarios through 'rematching', importing/exporting.  For a detailed discussion, go to the Autoreplies section of PlaySms and click on 'help'.

Can also now export data from inbox/outbox to csv format for analysis (e.g. in Excel).


# Details #
  * update to autoreply gui; can now show/hide groups of scenarios without having to edit them; editing a scenario now simpler
  * added variables to autoreplies: ##KEYWORDS##, ##SUBKEYWORDS##, ##KEYWORD0##, ##KEYWORD1##, etc
  * added special ##REMATCH## variable that causes the contents of an autoreply to be matched again as if it were an incoming text; this allows for autoreplies to be aliases for one another
  * made autoreply keyword matching more robust in its keyword parsing, the requirements for users conforming to the keyword syntax are not quite so rigid
  * added a 'test' feature to autoreplies, which allows for testing of keyword matching through the playsms web interface; now you don't have to text the system when you want to check whether a given set of keywords does what you want
  * added a 'help' link to autoreplies, which shows a detailed explanation on how variables and rematching works
  * added autoreply exporting/importing to/from ini files; now a set of autoreplies can be easily backed up or shared between playsms users!
  * added inbox/outbox csv exporting for both full data and histogram (this csv file can be imported into excel for analysis/graphing)