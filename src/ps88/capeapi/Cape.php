<?php
    namespace ps88\capeapi;


    use pocketmine\entity\Skin;
    use pocketmine\Server;

    class Cape{

        /** @var string */
        private $name;

        /** @var string */
        private $capedata;

        /**
         * Cape constructor.
         * @param string $name
         * @param string $capedata
         */
        public function __construct(string $name, string $capedata) {
            $this->name = $name;
            $this->capedata = $capedata;
        }

        /**
         * @return string
         */
        public function getName(): string {
            return $this->name;
        }

        /**
         * @param Skin $skin
         * @return Skin
         */
        public function patchCapeToSkin(Skin $skin): Skin{
            $skin = new Skin($skin->getSkinId(), $skin->getSkinData(), $this->capedata, $skin->getGeometryName(), $skin->getGeometryData());
            return $skin;
        }

        /**
         * @return string
         */
        public function getCapedata(): string {
            return $this->capedata;
        }
    }