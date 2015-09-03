function goInit(options) {
    var g = go.GraphObject.make;  // for conciseness in defining templates

    // set up any given options
    this.options = {
        makePalette: true,
        allowKeyChange: true,
        readOnly: false
    };
    if (typeof options === 'object') this.options = $.extend(true, this.options, options);

    // decide whether to show context menu based on options
    if (this.options.allowKeyChange) {
        var contextMenu = g(go.Adornment, "Vertical",
            g("ContextMenuButton", g(go.TextBlock, "Change key"), {click: changeKey})
        );
    } else {
        var contextMenu = null;
    }


    myDiagram =
        g(go.Diagram, "flowchart",  // must name or refer to the DIV HTML element
            {
                initialContentAlignment: go.Spot.Center,
                allowDrop: true,  // must be true to accept drops from the Palette
                isReadOnly: this.options.readOnly,
                "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
                "LinkRelinked": showLinkLabel,
                "animationManager.duration": 800, // slightly longer than default (600ms) animation
                "undoManager.isEnabled": true  // enable undo & redo
            });

    // when the document is modified, add a "*" to the title and enable the "Save" button
    /*myDiagram.addDiagramListener("Modified", function (e) {
        var button = document.getElementById("SaveButton");
        if (button) button.disabled = !myDiagram.isModified;
        var idx = document.title.indexOf("*");
        if (myDiagram.isModified) {
            if (idx < 0) document.title += "*";
        } else {
            if (idx >= 0) document.title = document.title.substr(0, idx);
        }
    });*/
    /*myDiagram.addDiagramListener("ObjectSingleClicked", function (e, obj) {
        console.log(obj);
    });*/

    // helper definitions for node templates

    function nodeStyle() {
        return [
            // The Node.location comes from the "loc" property of the node data,
            // converted by the Point.parse static method.
            // If the Node.location is changed, it updates the "loc" property of the node data,
            // converting back using the Point.stringify static method.
            new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
            {
                // the Node.location is at the center of each node
                locationSpot: go.Spot.Center,
                //isShadowed: true,
                //shadowColor: "#888",
                // handle mouse enter/leave events to show/hide the ports
                mouseEnter: function (e, obj) {
                    showPorts(obj.part, true);
                },
                mouseLeave: function (e, obj) {
                    showPorts(obj.part, false);
                }
            }
        ];
    }

    // Define a function for creating a "port" that is normally transparent.
    // The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
    // and where the port is positioned on the node, and the boolean "output" and "input" arguments
    // control whether the user can draw links from or to the port.
    function makePort(name, spot, output, input) {
        // the port is basically just a small circle that has a white stroke when it is made visible
        return g(go.Shape, "Circle",
            {
                fill: "transparent",
                stroke: null,  // this is changed to "white" in the showPorts function
                desiredSize: new go.Size(8, 8),
                alignment: spot, alignmentFocus: spot,  // align the port on the main Shape
                portId: name,  // declare this object to be a "port"
                fromSpot: spot, toSpot: spot,  // declare where links may connect at this port
                fromLinkable: output, toLinkable: input,  // declare whether the user may draw links to/from here
                cursor: "pointer"  // show a different cursor to indicate potential link point
            });
    }

    // define the Node templates for regular nodes

    var lightText = 'whitesmoke';

    myDiagram.nodeTemplateMap.add("",  // the default category
        g(go.Node, "Spot", nodeStyle(),
            // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
            g(go.Panel, "Auto",
                g(go.Shape, "Rectangle",
                    {fill: "#00A9C9", stroke: null},
                    new go.Binding("fill", "color"),
                    new go.Binding("figure", "figure")),
                g(go.TextBlock,
                    {
                        font: "8pt Helvetica, Arial, sans-serif",
                        stroke: lightText,
                        margin: 8,
                        maxSize: new go.Size(160, NaN),
                        wrap: go.TextBlock.WrapFit,
                        editable: true
                    },
                    new go.Binding("text", "text").makeTwoWay()),
                {
                    contextMenu: contextMenu,
                    toolTip: g(go.Adornment, "Auto",
                        g(go.Shape, {fill: "#FFFFCC"}),
                        g(go.Panel, "Vertical",
                            g(go.TextBlock, {textAlign: "left", margin: 4}, new go.Binding("text", "key", function (key) {
                                return "Key: " + key;
                            })),
                            g(go.TextBlock, {textAlign: "left", margin: 4}, new go.Binding("text", "data", function (data) {
                                if(data) {
                                    return "-- Data -- " + data;
                                }else{
                                    return "";
                                }
                                return;
                            }))
                        )
                    )
                }
            ),
            // four named ports, one on each side:
            makePort("T", go.Spot.Top, false, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, false)
        ));

    myDiagram.nodeTemplateMap.add("Start",
        g(go.Node, "Spot", nodeStyle(),
            g(go.Panel, "Auto",
                g(go.Shape, "Circle",
                    {
                        minSize: new go.Size(40, 40),
                        maxSize: new go.Size(100, 100),
                        fill: "#79C900",
                        stroke: null
                    },
                    new go.Binding("fill", "color")//binding for change color when highlighting
                ),
                g(go.TextBlock, "Start",
                    {
                        margin: 2,
                        font: "8pt Helvetica, Arial, sans-serif",
                        stroke: lightText,
                        editable: true,
                        isMultiline: true,
                        maxSize: new go.Size(60, 60),
                        wrap: go.TextBlock.WrapFit
                    },
                    new go.Binding("text", "text").makeTwoWay()//bind for change of inside text
                ),
                {//change of key on right click menu
                    contextMenu: contextMenu,
                    toolTip: g(go.Adornment, "Auto",
                        g(go.Shape, {fill: "#FFFFCC"}),
                        g(go.TextBlock, {margin: 4}, new go.Binding("text", "key", function (key) {
                            return "Key: " + key;
                        }))
                    )
                }
            ),
            // three named ports, one on each side except the top, all output only:
            makePort("L", go.Spot.Left, true, false),
            makePort("R", go.Spot.Right, true, false),
            makePort("B", go.Spot.Bottom, true, false)
        ));

    myDiagram.nodeTemplateMap.add("End",
        g(go.Node, "Spot", nodeStyle(),
            g(go.Panel, "Auto",
                g(go.Shape, "Circle",
                    {
                        minSize: new go.Size(40, 40),
                        maxSize: new go.Size(100, 100),
                        fill: "#DC3C00",
                        stroke: null
                    },
                    new go.Binding("fill", "color")//binding for change color when highlighting
                ),
                g(go.TextBlock, "End",
                    {
                        margin: 2,
                        font: "8pt Helvetica, Arial, sans-serif",
                        stroke: lightText,
                        editable: true,
                        isMultiline: true,
                        maxSize: new go.Size(60, 60),
                        wrap: go.TextBlock.WrapFit
                    },
                    new go.Binding("text", "text").makeTwoWay()//bind for change of inside text
                ),
                {//change of key on right click menu
                    contextMenu: contextMenu,
                    toolTip: g(go.Adornment, "Auto",
                        g(go.Shape, {fill: "#FFFFCC"}),
                        g(go.TextBlock, {margin: 4}, new go.Binding("text", "key", function (key) {
                            return "Key: " + key;
                        }))
                    )
                }
            ),
            // three named ports, one on each side except the bottom, all input only:
            makePort("T", go.Spot.Top, false, true),
            makePort("L", go.Spot.Left, false, true),
            makePort("R", go.Spot.Right, false, true)
        )
    );

    myDiagram.nodeTemplateMap.add("Error",
        g(go.Node, "Spot", nodeStyle(),
            g(go.Panel, "Auto",
                g(go.Shape, "Circle",
                    {minSize: new go.Size(40, 40), fill: "#E60000", stroke: null}),
                g(go.TextBlock, "Err",
                    {margin: 5, font: "8pt Helvetica, Arial, sans-serif", stroke: lightText})
            ),
            // three named ports, one on each side except the bottom, all input only:
            makePort("T", go.Spot.Top, false, true),
            makePort("L", go.Spot.Left, false, true),
            makePort("R", go.Spot.Right, false, true),
            makePort("B", go.Spot.Bottom, false, true)
        ));

    myDiagram.nodeTemplateMap.add("Comment",
        g(go.Node, "Auto", nodeStyle(),
            g(go.Shape, "File",
                {fill: "#FFFFFF", stroke: null}),
            g(go.TextBlock,
                {
                    margin: 5,
                    maxSize: new go.Size(200, NaN),
                    wrap: go.TextBlock.WrapFit,
                    textAlign: "center",
                    editable: true,
                    font: "bold 8pt Helvetica, Arial, sans-serif",
                    stroke: '#454545'
                },
                new go.Binding("text", "text").makeTwoWay())
            // no ports, because no links are allowed to connect with a comment
        ));
    // replace the default Link template in the linkTemplateMap
    myDiagram.linkTemplate =
        g(go.Link,  // the whole link panel
            {
                routing: go.Link.AvoidsNodes,
                curve: go.Link.JumpOver,
                corner: 5, toShortLength: 4,
                relinkableFrom: true,
                relinkableTo: true,
                reshapable: true
            },
            new go.Binding("points").makeTwoWay(),
            g(go.Shape,  // the link path shape
                {isPanelMain: true, stroke: "gray", strokeWidth: 2},
                new go.Binding("stroke", "color")),
            g(go.Shape,  // the arrowhead
                {toArrow: "standard", stroke: null, fill: "gray"},
                new go.Binding("fill", "color")),
            g(go.Panel, "Auto",  // the link label, normally not visible
                {visible: false, name: "LABEL", segmentIndex: 2, segmentFraction: 0.5},
                new go.Binding("visible", "visible").makeTwoWay(),
                g(go.Shape, "RoundedRectangle",  // the label shape
                    {fill: "#F8F8F8", stroke: null}),
                g(go.TextBlock, "Yes",  // the label
                    {
                        textAlign: "center",
                        font: "10pt helvetica, arial, sans-serif",
                        stroke: "#333333",
                        editable: true
                    },
                    new go.Binding("text", "text").makeTwoWay())
            )
        );

    // Make link labels visible if coming out of a "conditional" node.
    // This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.
    function showLinkLabel(e) {
        var label = e.subject.findObject("LABEL");
        if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Hexagon");
    }

    // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
    myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
    myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;

    load();  // load an initial diagram from some JSON text

    // initialize the Palette that is on the left side of the page
    if (this.options.makePalette) {
        myPalette =
            g(go.Palette, "control-tray",  // must name or refer to the DIV HTML element
                {
                    "animationManager.duration": 800, // slightly longer than default (600ms) animation
                    nodeTemplateMap: myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
                    model: new go.GraphLinksModel([  // specify the contents of the Palette
                        {category: "Start", text: "Start"},
                        {text: "Step"},
                        {text: "???", figure: "Hexagon"},
                        {category: "End", text: "End"},
                        {category: "Error", text: "Err"},
                        {category: "Comment", text: "Comment", figure: "RoundedRectangle"}
                    ])
                });
    }


}

// Make all ports on a node visible when the mouse is over the node
function showPorts(node, show) {
    var diagram = node.diagram;
    if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
    node.ports.each(function (port) {
        port.stroke = (show ? "white" : null);
    });
}


// Show the diagram's model in JSON format that the user may edit
function save() {
    $("#flowchart-json").text(myDiagram.model.toJson());
    myDiagram.isModified = false;
}
function load() {
    myDiagram.model = go.Model.fromJson($("#flowchart-json").text());
}

function changeKey(e, obj) {
    var contextMenu = obj.part;
    var nodeData = contextMenu.data;
    var newKey = prompt("Enter new node key for \"" + nodeData.key.toString() + "\"");

    if (typeof newKey === 'string' && newKey.length) {
        var nodes = myDiagram.model.nodeDataArray;
        var duplicateKey = false;

        for (var i = nodes.length - 1; i >= 0; i--) {
            // check for dupe keys (but don't compare to self)
            // force string comparison cos default keys are numbers, "12" !== 12
            if (newKey === nodes[i].key.toString() && nodes[i] !== nodeData) {
                duplicateKey = true;
                break;
            }
        }
        ;

        if (duplicateKey) {
            alert("The key \"" + newKey + "\" is already in use. Please use a unqiue key.")
        } else {
            // all good, apply new key
            myDiagram.startTransaction("changed key");
            myDiagram.model.setDataProperty(nodeData, 'key', newKey);
            save();
            myDiagram.commitTransaction("changed key");
        }
    }
}

// add an SVG rendering of the diagram at the end of this page
function makeSVG() {
    var svg = myDiagram.makeSvg({
        scale: 0.2
    });
    svg.style.border = "1px solid black";
    obj = document.getElementById("SVGArea");
    obj.appendChild(svg);
    if (obj.children.length > 0)
        obj.replaceChild(svg, obj.children[0]);
}