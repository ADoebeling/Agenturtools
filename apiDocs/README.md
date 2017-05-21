# Agenturtools

## Table of Contents

* [cmd](#cmd)
    * [setCmd](#setcmd)
    * [setRegEx](#setregex)
    * [exec](#exec)
    * [getRawArray](#getrawarray)
    * [getParsed](#getparsed)
    * [run](#run)
* [exception](#exception)
    * [errorMessage](#errormessage)
* [gitSniffer](#gitsniffer)
    * [getGitStatusAsParsedMarkdown](#getgitstatusasparsedmarkdown)
    * [getGitDiffAsParsedMarkdown](#getgitdiffasparsedmarkdown)
    * [getTextAsParsedMarkdown](#gettextasparsedmarkdown)
    * [sendIssue](#sendissue)

## cmd





* Full name: \www1601com\Agenturtools\cmd


### setCmd



```php
cmd::setCmd(  $cmd )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cmd` | **** |  |




---

### setRegEx



```php
cmd::setRegEx(  $regEx )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$regEx` | **** |  |




---

### exec



```php
cmd::exec(  )
```







---

### getRawArray



```php
cmd::getRawArray(  )
```







---

### getParsed



```php
cmd::getParsed( string $returnFormat = &#039;array&#039; ): array|boolean|string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$returnFormat` | **string** | 'array'&#124;'string' |




---

### run

Run a command on shell, optional parse the result and return as array

```php
cmd::run( string $cmd, string $regex = &#039;&#039;, string $returnFormat = &#039;array&#039; ): array|string|boolean
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cmd` | **string** |  |
| `$regex` | **string** |  |
| `$returnFormat` | **string** |  |




---

## exception





* Full name: \www1601com\Agenturtools\exception
* Parent class: 


### errorMessage



```php
exception::errorMessage(  )
```







---

## gitSniffer

Class gitSniffer

Checks a git-repo for not yet committed changes and sends a friendly reminder as Github-Issue.

---------------------------------------------------------

LABEL: BUG, GitSnifferReminder
SUBJECT: :eyeglasses: Commits missing: index.php, test.jpg, bla.sdf, sdfsdf.txt, ... (23 more)

---------------------------------------------------------

@1601com/DigitalMedia The GitSniffer has found 5 changes at `/var/ww/bla/blubb/project` in branch `deployment/blubb`
that were not committed yet.
Please take two minutes to commit these files or update the .gitignore if these files should not be part of the repo.

## 1 new or untracked file/folder:

* [ ] `file_1` (2017-05-03 15:22)

## 2 modified files/folders:

* [ ] `file_2` (2017-05-03 15:22)
* [ ] `file_3` (2017-05-03 15:22)

## 3 deleted files/folders

* [ ] `file_4` (2017-05-03 15:22)
* [ ] `file_5` (2017-05-03 15:22)
* [ ] `file_6` (2017-05-03 15:22)

## Changes

```diff
git diff
diff --git a/file2 b/file2
index e69de29..bee906d 100644
--- a/file2
+++ b/file2
@@ -0,0 +1,6 @@
+sfdsfd
+sdf
+sdf
+sfd
+sdf
```

## Copy & paste: Adding listed files to git

```
cd /var/ww/bla/blubb/project

git add file_1 # Adding the untracked file
git add file_2 # Adding the modified file
git add file_3 # Adding the modified file
git rm -r file_4 # Removing the deleted file or folder
git rm -r file_5 # Removing the deleted file or folder
git rm -r file_6 # Removing the deleted file or folder

git commit -m "DESCRIPTION OF YOUR CHANGES WITH ISSUE-ID" --author="XXX <xx@xxx.xx>"
git push
```

## ... OR: Exclude listed files in `.gitignore`

```
/file_1
/file_2
/file_3
```

```
cd /var/ww/bla/blubb/project

git rm -r --cached file_1 # Remove file from git
git rm -r --cached file_2 # Adding the modified file
git rm -r --cached file_3 # Adding the modified file
git rm -r --cached file_4 # Removing the deleted file or folder
git rm -r --cached file_5 # Removing the deleted file or folder
git rm -r --cached file_6 # Removing the deleted file or folder

git add .gitignore
git commit -m "DESCRIPTION OF YOUR CHANGES WITH ISSUE-ID" --author="XXX <xx@xxx.xx>"
git push
```

Thanks a lot!
- [GitSniffer](https://github.com/ADoebeling/GitSniffer) by @ADoebeling

<!-- Protocol: LINK -->


Request:
$domain.tld/gitSniffer.php?repo=ADoebeling/test&user=XXX&token=XXX

---------------------------------------------------------

If the script was triggered by a updated Issue, it will additionally post a comment:

---------------------------------------------------------

* Full name: \www1601com\Agenturtools\gitSniffer

**See Also:**

* https://github.com/ADoebeling/GitSniffer 

### getGitStatusAsParsedMarkdown



```php
gitSniffer::getGitStatusAsParsedMarkdown(  )
```



* This method is **static**.



---

### getGitDiffAsParsedMarkdown

Parse the diff returned by `git diff`

```php
gitSniffer::getGitDiffAsParsedMarkdown( string $cmd = &#039;git diff --minimal | head -n40&#039; ): string
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cmd` | **string** |  |




---

### getTextAsParsedMarkdown



```php
gitSniffer::getTextAsParsedMarkdown(  )
```



* This method is **static**.



---

### sendIssue



```php
gitSniffer::sendIssue(  )
```



* This method is **static**.



---



--------
> This document was automatically generated from source code comments on 2017-05-21 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
