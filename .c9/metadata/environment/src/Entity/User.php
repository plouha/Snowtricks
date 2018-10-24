{"filter":false,"title":"User.php","tooltip":"/src/Entity/User.php","undoManager":{"mark":14,"position":14,"stack":[[{"start":{"row":50,"column":76},"end":{"row":50,"column":97},"action":"insert","lines":[", cascade={\"persist\"}"],"id":6}],[{"start":{"row":50,"column":98},"end":{"row":51,"column":0},"action":"insert","lines":["",""],"id":7},{"start":{"row":51,"column":0},"end":{"row":51,"column":7},"action":"insert","lines":["     * "]}],[{"start":{"row":51,"column":7},"end":{"row":51,"column":101},"action":"insert","lines":["     * @ORM\\OneToMany(targetEntity=\"App\\Entity\\Comment\", mappedBy=\"user\", cascade={\"persist\"})"],"id":8}],[{"start":{"row":51,"column":5},"end":{"row":51,"column":12},"action":"remove","lines":["*      "],"id":9}],[{"start":{"row":51,"column":0},"end":{"row":51,"column":94},"action":"remove","lines":["     * @ORM\\OneToMany(targetEntity=\"App\\Entity\\Comment\", mappedBy=\"user\", cascade={\"persist\"})"],"id":10}],[{"start":{"row":51,"column":0},"end":{"row":52,"column":0},"action":"remove","lines":["",""],"id":11}],[{"start":{"row":50,"column":98},"end":{"row":51,"column":0},"action":"insert","lines":["",""],"id":13},{"start":{"row":51,"column":0},"end":{"row":51,"column":7},"action":"insert","lines":["     * "]}],[{"start":{"row":51,"column":7},"end":{"row":51,"column":124},"action":"insert","lines":["     * @ORM\\OneToMany(targetEntity=\"App\\Entity\\Comment\", mappedBy=\"article\", orphanRemoval=true, cascade={\"persist\"})"],"id":14}],[{"start":{"row":51,"column":5},"end":{"row":51,"column":12},"action":"remove","lines":["*      "],"id":15}],[{"start":{"row":50,"column":67},"end":{"row":50,"column":75},"action":"remove","lines":["relation"],"id":16},{"start":{"row":50,"column":67},"end":{"row":50,"column":68},"action":"insert","lines":["c"]},{"start":{"row":50,"column":68},"end":{"row":50,"column":69},"action":"insert","lines":["o"]},{"start":{"row":50,"column":69},"end":{"row":50,"column":70},"action":"insert","lines":["m"]},{"start":{"row":50,"column":70},"end":{"row":50,"column":71},"action":"insert","lines":["m"]},{"start":{"row":50,"column":71},"end":{"row":50,"column":72},"action":"insert","lines":["e"]},{"start":{"row":50,"column":72},"end":{"row":50,"column":73},"action":"insert","lines":["n"]},{"start":{"row":50,"column":73},"end":{"row":50,"column":74},"action":"insert","lines":["t"]}],[{"start":{"row":51,"column":0},"end":{"row":51,"column":117},"action":"remove","lines":["     * @ORM\\OneToMany(targetEntity=\"App\\Entity\\Comment\", mappedBy=\"article\", orphanRemoval=true, cascade={\"persist\"})"],"id":17},{"start":{"row":50,"column":97},"end":{"row":51,"column":0},"action":"remove","lines":["",""]}],[{"start":{"row":50,"column":67},"end":{"row":50,"column":74},"action":"remove","lines":["comment"],"id":18},{"start":{"row":50,"column":67},"end":{"row":50,"column":68},"action":"insert","lines":["u"]},{"start":{"row":50,"column":68},"end":{"row":50,"column":69},"action":"insert","lines":["s"]},{"start":{"row":50,"column":69},"end":{"row":50,"column":70},"action":"insert","lines":["e"]},{"start":{"row":50,"column":70},"end":{"row":50,"column":71},"action":"insert","lines":["r"]}],[{"start":{"row":48,"column":0},"end":{"row":51,"column":7},"action":"remove","lines":["","    /**","     * @ORM\\OneToMany(targetEntity=\"App\\Entity\\Comment\", mappedBy=\"user\", cascade={\"persist\"})","     */"],"id":21}],[{"start":{"row":48,"column":0},"end":{"row":54,"column":5},"action":"remove","lines":["","    private $comments;","","    public function __construct()","    {","        $this->comments = new ArrayCollection();","    }"],"id":22}],[{"start":{"row":116,"column":0},"end":{"row":123,"column":5},"action":"remove","lines":["","    /**","     * @return Collection|Comment[]","     */","    public function getComments(): Collection","    {","        return $this->comments;","    }"],"id":23},{"start":{"row":116,"column":0},"end":{"row":117,"column":0},"action":"remove","lines":["",""]}]]},"ace":{"folds":[],"scrolltop":2280,"scrollleft":0,"selection":{"start":{"row":116,"column":0},"end":{"row":116,"column":0},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":0},"timestamp":1539174207079,"hash":"5273bdab8fa61be60d9bc269617e05157ae5ae4f"}