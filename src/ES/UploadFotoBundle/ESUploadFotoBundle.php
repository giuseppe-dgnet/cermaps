<?php

namespace ES\UploadFotoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ESUploadFotoBundle extends Bundle
{
     public function getParent() {
        return 'PunkAveFileUploaderBundle';
    }
}
