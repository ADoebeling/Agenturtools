!#/bin/bash

php7-70STABLE-CLI ../vendor/bin/phpdoc --directory=../class --target=. --template="../vendor/cvuorinen/phpdoc-markdown-public/data/templates/markdown-public" --title="PHP-Documentation of the Agenturtools" --cache-folder=../tmp/

rm ../phpDocumentation.md
mv README.md ../phpDocumentation.md

git add ../phpDocumentation.md
git commit -m "Updating the auto-generated phpDoc"