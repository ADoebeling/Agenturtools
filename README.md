# Agenturtools

The Agenturtools (EN: agency tools) are a collection of tools used by [1601.communication](https://www.1601.com) to manage its customer git repositories.   
    
<img src=readme_files/screenshotGitSniffer_v0.2.170525.png width=300 align=right>

## Tool #1: GitSniffer (beta)

The GitSniffer is searching for uncommitted files (in shared development repositories). If you setup the script as cronjob it will create friendly reminders to your team, if they forgot to commit their changes.



### Installation
* Clone this repository  
`git clone git@github.com:ADoebeling/Agenturtools.git`
* Place the folder at your project in the git repository 
* Add and edit your github config  
`cp config/github.default.php config/github.php; vi config/github.php`
* Run the script   
`./scripts/runGitSniffer.php`


## Tool #2: GitCommmitter (wip)

The GitCommitter is a web based script to review your changes in a git repository, to commit und push these changes to Github.
 
**Status:** work in progress 
