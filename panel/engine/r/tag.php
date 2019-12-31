<?php

// This is the simplest way to disable the public tag route, that is, by renaming the tag path from `/tag` to another
// It will be safe if you have custom tag path name other than `/tag`, but in most cases, user(s) will keep them untouched anyway
// so that the tag path will be the same as the tag folder name that is used to store the tag file(s), that likely will break the tag route
State::get('x.tag') && State::set('x.tag.path', P);