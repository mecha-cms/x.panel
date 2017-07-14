<?php

return '<p>' . Form::text('!+[id]', $__page[0]->id, time(), ['classes' => ['input', 'block'], 'id' => 'f-id', 'ondblclick' => 'this.removeAttribute(\'readonly\'),this.focus(),this.select();', 'onblur' => 'this.setAttribute(\'readonly\',!0);']) . '</p>';