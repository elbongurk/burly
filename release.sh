THEME_DIR=$(pwd)
BUILD_DIR=$THEME_DIR/build
TEMP_DIR=$BUILD_DIR/burly

composer update

mkdir -p $TEMP_DIR

#########################
# COPY THEME
#########################

echo "Copying files"

cp $THEME_DIR/*.md $TEMP_DIR
cp $THEME_DIR/*.php $TEMP_DIR
cp $THEME_DIR/*.css $TEMP_DIR
cp $THEME_DIR/*.png $TEMP_DIR

cp -r $THEME_DIR/src $TEMP_DIR
cp -r $THEME_DIR/vendor $TEMP_DIR

#########################
# CLEAN HANDLEBARS
#########################

LIB_DIR=$TEMP_DIR/vendor/xamin/handlebars.php
rm -r $LIB_DIR/tests

#########################
# CLEAN CUSTOM META BOXES
#########################

LIB_DIR=$TEMP_DIR/vendor/humanmade/custom-meta-boxes
rm -r $LIB_DIR/tests

#########################
# CLEAN INFLECTOR
#########################

LIB_DIR=$TEMP_DIR/vendor/icanboogie/inflector
rm -r $LIB_DIR/tests

#########################
# ZIP THEME
#########################

echo "Zipping files"

cd $BUILD_DIR && zip -qr burly.zip burly -x "*/\.*" -x "\.*"
rm -rf $TEMP_DIR

echo "Done."
