(function() {

	function Rect() {
		this.x = 0;
		this.y = 0;
		this.width = -1;
		this.height = -1;
	};

	var m_vAllNodes = new Array();
	var m_iFontLineHeight = 0,
		m_iFontLineDescent = 0,
		m_yLine = 0,
		m_iInterBoxSpace = 10,
		m_iBoxBufferSpace = 2,
		m_iNodeRounding = 4,
		m_iTallestBoxSize = 0,
		m_iToolbarYPad = 15,
		m_iMinBoxWidth = 40,
		m_iToolbarXPos = 0,
		m_iToolbarYPos = 0,
		m_bCornered = false,
		BOX_Y_DELTA = 40,
		iMaxHoverPicHeight = 150,
		iMaxHoverPicWidth = 150,
		aCurrentHoverPic = null,
		aFamilyTreeElement = null,
		sUnknownGenderLetter = null,
		bOneNamePerLine = true,
		bOnlyFirstName = false,
		bBirthAndDeathDates = true,
		bBirthAndDeathDatesOnlyYear = true,
		bBirthDatePrefix = true,
		bDeathDatePrefix = true,

		bConcealLivingDates = true,
		bShowSpouse = true,
		bShowOneSpouse = false,
		bVerticalSpouses = false,
		bMaidenName = true,
		bShowGender = true,
		bDiagonalConnections = false,
		bRefocusOnClick = false,
		bShowToolbar = true;
	var m_Canvas,
		m_CanvasRect;
	var iCanvasWidth = 100,
		iCanvasHeight = 100;

	this.familytreemain = function() {
		aFamilyTreeElement = document.getElementById("familytree");
		if(!aFamilyTreeElement) {
			return;
		}
		m_Canvas = Raphael("familytree", iCanvasWidth, iCanvasHeight);
		m_Canvas.clear();
		m_CanvasRect = m_Canvas.rect(0, 0, iCanvasWidth, iCanvasHeight, 10).attr({
			fill: canvasbgcol,
			stroke: "none"
		}).toBack();
		text_sStartName = document.getElementById("focusperson");
		hoverpic = document.getElementById("hoverimage");
		createTreeFromArray(tree_txt);
		loadImages();
		loadDivs();
		loadShortInfo();
		loadLongInfo();
		redrawTree();
	};

	this.redrawTree = function() {
		text_sStartName.value = text_sStartName.value.replace("\n", "");
		text_sStartName.value = text_sStartName.value.replace("\n", "");
		var sPerson = text_sStartName.value;
		var n = find(sPerson);
		if(n == null) {
			return;
		}
		iCanvasWidth = 100;
		iCanvasHeight = 100;
		m_Canvas.clear();
		freeNodesAllocatedTexts();
		resetObjectStates();
		m_CanvasRect = m_Canvas.rect(0, 0, iCanvasWidth, iCanvasHeight, 10).attr({
			fill: canvasbgcol,
			stroke: "none"
		}).toBack();
		printTreeFromNode(sPerson);
	};


	function Node(sID) {
		var m_sFTID = sID,
			m_sName = "?",
			m_sImageURL = null,
			m_HoverPic = null,
			m_MyToolbarDiv = null,
			m_MyThumbnailDiv = null,
			m_sShortInfoURL = null,
			m_sLongInfoURL = null,
			m_sMaiden = null,
			m_iBirthYear = -1,
			m_sGender = sUnknownGenderLetter,
			m_iMyBranchWidth = 0;
		var m_vParents = new Array(),
			m_vChildren = new Array(),
			m_vSpouses = new Array();
		var m_MyRect = new Rect(),
			m_BothRect = new Rect();
		var m_RaphRect,
			m_RaphTexts = new Array();
		var m_sBirthday = null;
		var m_sDeathday = null;
		m_vAllNodes.push(this);

		this.setSpouse = function(sID) {
			var nSpouse = findOrCreate(sID);
			connectSpouses(this, nSpouse);
		};
		this.setMaiden = function(sMaidenName) {
			m_sMaiden = sMaidenName;
		};
		this.addParent = function(sParentID) {
			var nParent = findOrCreate(sParentID);
			connectParentChild(nParent, this);
			return nParent;
		};
		this.setBirthYear = function(iYear) {
			m_iBirthYear = iYear;
		};
		this.setBirthday = function(sDate) {
			m_sBirthday = sDate;
		};
		this.setDeathday = function(sDate) {
			m_sDeathday = sDate;
		};
		this.getBirthday = function() {
			return m_sBirthday;
		};
		this.getDeathday = function() {
			return m_sDeathday;
		};
		this.setGender = function(sGenderLetter) {
			var tmp = sGenderLetter.toLowerCase();
			if((tmp != "f") && (tmp != "m")) {
				tmp = sUnknownGenderLetter;
			}
			m_sGender = tmp;
		};
		this.setImageURL = function(sURL) {
			m_sImageURL = sURL;
			m_HoverPic = new Image();
		};
		this.setToolbarDiv = function(sDivName) {
			m_MyToolbarDiv = document.getElementById(sDivName);
		};
		this.setThumbnailDiv = function(sDivName) {
			m_MyThumbnailDiv = document.getElementById(sDivName);
		};
		this.setShortInfoURL = function(sURL) {
			m_sShortInfoURL = sURL;
		};
		this.setLongInfoURL = function(sURL) {
			m_sLongInfoURL = sURL;
		};
		this.getRaphRect = function() {
			return this.m_RaphRect;
		};
		this.getRaphTexts = function() {
			return this.m_RaphTexts;
		};
		this.setRaphTexts = function(arr) {
			this.m_RaphTexts = arr;
		};
		this.getFTID = function() {
			return m_sFTID;
		};
		this.setFTID = function(sID) {
			this.m_sFTID = sID;
		};
		this.getName = function() {
			return m_sName;
		};
		this.setName = function(sName) {
			m_sName = sName;
		};
		this.getImageURL = function() {
			return m_sImageURL;
		};
		this.getToolbarDiv = function() {
			return m_MyToolbarDiv;
		};
		this.getThumbnailDiv = function() {
			return m_MyThumbnailDiv;
		};
		this.getImage = function() {
			return m_HoverPic;
		};
		this.getShortInfoURL = function() {
			return m_sShortInfoURL;
		};
		this.getLongInfoURL = function() {
			return m_sLongInfoURL;
		};
		this.getChildren = function() {
			return m_vChildren;
		};
		this.getParents = function() {
			return m_vParents;
		};
		this.getMyRect = function() {
			return m_MyRect;
		};
		this.getBothRect = function() {
			return m_BothRect;
		};
		this.getSpouses = function() {
			return m_vSpouses;
		}
		this.hasPartner = function(n) {
			var i = 0;
			var len = m_vSpouses.length;
			while(i < len) {
				if(m_vSpouses[i++] == n) return true;
			}
			return false;
		};
		this.countParentGenerations = function() {
			var iCurrentDepth = 0;
			if(this.m_vParents != undefined) {
				var i = 0;
				var len = this.m_vParents.length;
				while(i < len) {
					var p = this.m_vParents[i++];
					iCurrentDepth = Math.max(iCurrentDepth, p.countParentGenerations());
				}
			}
			return 1 + iCurrentDepth;
		};
		this.countChildrenGenerations = function() {
			var iCurrentDepth = 0,
				iNumChildren = m_vChildren.length;
			var i = 0;
			var len = m_vChildren.length;
			while(i < len) {
				iCurrentDepth = Math.max(iCurrentDepth, m_vChildren[i++].countChildrenGenerations());
			}
			if(iNumChildren != 0) return 1 + iCurrentDepth;
			else return 0;
		};
		this.calcParentBranchWidths = function() {
			return 0;
		};
		this.calcChildrenBranchWidths = function() {
			this.getMeAndSpousesGraphBoxes();
			var iMyWidth = m_iInterBoxSpace + m_BothRect.width;
			this.m_iMyBranchWidth = 0;
			var i = 0;
			var len = m_vChildren.length;
			while(i < len) {
				this.m_iMyBranchWidth += m_vChildren[i++].calcChildrenBranchWidths();
			}
			if(iMyWidth > this.m_iMyBranchWidth) this.m_iMyBranchWidth = iMyWidth;
			return this.m_iMyBranchWidth;
		};
		this.graphMe = function(iRelativePos, iGeneration) {
			var iY = this.getBoxY(iGeneration),
				iX = iRelativePos;
			iX += this.m_iMyBranchWidth / 2;
			this.getGraphBox(iX, iY);
		};
		this.graphChildren = function(iRelativePos, iGeneration) {
			var iTotWidth = 0;
			var i = 0;
			var len = m_vChildren.length;
			while(i < len) {
				var n = m_vChildren[i++];
				iTotWidth += n.m_iMyBranchWidth;
			}
			var iY = this.getBoxY(iGeneration),
				iX = iRelativePos - iTotWidth / 2;
			i = 0;
			var len = m_vChildren.length;
			while(i < len) {
				var n = m_vChildren[i];
				iX += n.m_iMyBranchWidth / 2;
				m_vChildren[i].getGraphBox(iX, iY);
				m_vChildren[i].graphChildren(iX, 1 + iGeneration);
				iX += n.m_iMyBranchWidth / 2;
				i++;
			}
		};
		this.graphConnections = function() {
			var iXFrom, iYFrom, iXTo, iYTo, iYMid;
			if(bShowSpouse && this.getSpouses().length != 0) {
				iXFrom = m_MyRect.x + (m_MyRect.width-72);
			} else {
				iXFrom = m_MyRect.x + ((m_MyRect.width-72) / 2);
			}

			iYFrom = m_MyRect.y + m_MyRect.height - m_iNodeRounding;
			var i = 0;
			var len = m_vChildren.length;
			while(i < len) {
				var n = m_vChildren[i++];
				iXTo = n.getMyRect().x + (n.getMyRect().width-72) / 2;
				iYTo = n.getMyRect().y;
				iYMid = Math.round(0.5 + (iYFrom + iYTo) / 2);
				if(bDiagonalConnections) {
					drawLine(iXFrom, iYFrom, iXTo, iYTo);
				} else {

					drawLine(iXFrom+1, iYFrom-2, iXFrom+1, iYMid);
					drawLine(iXFrom+1, iYMid, iXTo+1, iYMid);

					drawLine(iXTo+1, iYMid, iXTo+1, iYTo);
				}
				n.graphConnections();
			}
		};
		this.getBoxY = function(iRow) {
			return iRow * BOX_LINE_Y_SIZE + BOX_Y_DELTA;
		};
		this.getMeAndSpousesGraphBoxes = function() {
			this.getGraphBox(0, 0);
			m_BothRect.x = m_MyRect.x;
			m_BothRect.y = m_MyRect.y;
			m_BothRect.width = m_MyRect.width;
			m_BothRect.height = m_MyRect.height;
			var mySpouses = this.getSpouses();
			if(bShowSpouse && (mySpouses.length != 0)) {
				var iTotalSpouseHeight = 0;
				var iTotalSpouseWidth = 0;
				var iSpHeight = 0;
				var aSpouse;
				var i = 0;
				var len = mySpouses.length;
				while(i < len) {
					aSpouse = mySpouses[i++];
					aSpouse.getGraphBox(0, 0);
					iSpHeight = aSpouse.getMyRect().height;
					iSpWidth = aSpouse.getMyRect().width;
					if(bVerticalSpouses) {
						iTotalSpouseHeight += iSpHeight;
						iTotalSpouseWidth = Math.max(iTotalSpouseWidth, iSpWidth);
					} else {
						iTotalSpouseHeight = Math.max(iTotalSpouseHeight, iSpHeight);
						iTotalSpouseWidth += iSpWidth;
					}
					if(bShowOneSpouse) break;
				}
				iTotalSpouseHeight = Math.max(m_MyRect.height, iTotalSpouseHeight);
				m_BothRect.width += iTotalSpouseWidth;
				m_BothRect.height = iTotalSpouseHeight;
				m_MyRect.height = iTotalSpouseHeight;
				var i = 0;
				var len = mySpouses.length;
				while(i < len) {
					aSpouse = mySpouses[i++];
					if(bVerticalSpouses) {
						aSpouse.getMyRect().width = iTotalSpouseWidth;
					} else {
						aSpouse.getMyRect().height = iTotalSpouseHeight;
					}
					if(bShowOneSpouse) break;
				}
			}
			m_iTallestBoxSize = Math.max(m_iTallestBoxSize, m_MyRect.height);
		};

		this.getGraphBox = function(X, Y) {
			var bPrint = (X != 0) || (Y != 0);
			var r = new Rect();
			resetLine();
			if(!bPrint) {
				m_MyRect.width = m_iMinBoxWidth;
				m_MyRect.height = m_iToolbarYPad;
				m_BothRect.width = 0;
				m_BothRect.height = 0;
			}

			r.x = m_MyRect.x;
			r.y = m_MyRect.y;
			r.width = m_MyRect.width;
			r.height = m_MyRect.height;


			if(bPrint) {
				this.m_RaphRect = m_Canvas.rect();
				r.x = X - m_BothRect.width / 2;
				r.y = Y;
				growCanvas(r.x + r.width, r.y + r.height + 1);
				
				this.m_RaphRect.attr({
					"x": r.x,
					"y": r.y,
					"width": r.width,
					"height": r.height + 1,
					r: m_iNodeRounding
				});

				this.m_RaphRect.attr({
					stroke: nodeoutlinecol,
					fill: nodefillcol,
					"fill-opacity": nodefillopacity
				});

				this.m_RaphRect.show();

				this.m_RaphRect.click(function() {
					if(bRefocusOnClick) {
						var n = findRectOwningNode(this);
						if(n != null) {
							text_sStartName.value = n.getName();
							redrawTree();
						}
					}
				}).mouseover(function(ev) {

				

					this.animate({
						"fill-opacity": .75
					}, 300);
					var n = findRectOwningNode(this);
					if(n != null) {
						var im = n.getImage();
						if(im != null) {
							var coords = getPageEventCoords(ev);
							hoverpic.src = encodeURI(n.getImageURL());
							hoverpic.width = im.width;
							hoverpic.height = im.height;
							hoverpic.style.left = (coords.left + 20) + 'px';
							hoverpic.style.top = (coords.top - 10) + 'px';
							hoverpic.style.visibility = "visible";
						}
					}
				}).mouseout(function() {
					hoverpic.style.visibility = "hidden";
					this.animate({
						"fill-opacity": nodefillopacity
					}, 300);
				});
			}
			var sGender = (bShowGender && (m_sGender != null)) ? " (" + m_sGender + ")" : "";
			var sMaiden = (bMaidenName && (m_sMaiden != null)) ? " (" + m_sMaiden + ")" : "";

			//r = makeGraphBox(bPrint, r, 'up', this);

			if(bOnlyFirstName) {
				sTokens = this.getName().split(" ");
				r = makeGraphBox(bPrint, r, sTokens[0] + sGender, this);
			} else {
				if(bOneNamePerLine) {
					sTokens = this.getName().split(" ");
					for(var i = 0; i < sTokens.length; ++i) {
						if(i == sTokens.length - 1) r = makeGraphBox(bPrint, r, sTokens[i] + sGender, this);
						else r = makeGraphBox(bPrint, r, sTokens[i], this);
					}
					if(bMaidenName && (m_sMaiden != null)) r = makeGraphBox(bPrint, r, sMaiden, this);
				} else {
					r = makeGraphBox(bPrint, r, this.getName() + sGender + sMaiden, this);
				}
			}

			if(bBirthAndDeathDates) {
				var birth = this.getBirthday() != null ? this.getBirthday() : "",
				death = this.getDeathday() != null ? this.getDeathday() : "";


				if(death != "" || (birth != "" && !bConcealLivingDates)){

					if(bBirthAndDeathDatesOnlyYear){
						if(birth){
							birth = new Date(birth);
							birth = birth.getFullYear();
						}
						if(death){
							death = new Date(death);
							death = death.getFullYear();
						}
					}

					if(birth && death){
						r = makeGraphBox(bPrint, r, "("+bBirthDatePrefix + birth + "-"+bDeathDatePrefix + death + ")", this);
					} else if (birth && !death) {
						r = makeGraphBox(bPrint, r, "("+bBirthDatePrefix + birth + ")", this);
					} else if(!birth && death){
						r = makeGraphBox(bPrint, r, "("+bDeathDatePrefix + death + ")", this);
					} else {
						r = makeGraphBox(bPrint, r, '', this);
					}

				} else {
					if(bBirthAndDeathDatesOnlyYear){
						if(birth){
							birth = new Date(birth);
							birth = birth.getFullYear();
						}
					}
					if(birth){
						r = makeGraphBox(bPrint, r, "("+bBirthDatePrefix + birth + ")", this);
					} else {
						r = makeGraphBox(bPrint, r, '', this);
					}


				}
			} else {
				r = makeGraphBox(bPrint, r, '', this);
			}



			r.height += 3;
			r.width += 2;
			m_MyRect.x = r.x;
			m_MyRect.y = r.y;
			m_MyRect.width = r.width + 70;
			m_MyRect.height = r.height;
			if(bPrint && bShowSpouse) {
				var mySpouses = this.getSpouses();
				var aSpouse;
				var iH, iW;
				var xpos = r.x + r.width;
				var ypos = r.y;
				var i = 0;
				var len = mySpouses.length;
				while(i < len) {
					aSpouse = mySpouses[i++];
					iH = aSpouse.getMyRect().height;
					iW = aSpouse.getMyRect().width;
					bShowSpouse = false;
					aSpouse.getGraphBox(xpos, ypos);
					bShowSpouse = true;
					if(bShowOneSpouse) break;
					if(bVerticalSpouses) {
						ypos += iH;
					} else {
						xpos += iW;
					}
				}
			}
		};
		this.setImage = function(img) {
			m_HoverPic = img;
		};
		this.setDiv = function(div) {
			m_MyDiv = div;
		};
	}


	function getPageEventCoords(evt) {
		var coords = {
			left: 0,
			top: 0
		};
		if(evt.pageX) {
			coords.left = evt.pageX;
			coords.top = evt.pageY;
		} else if(evt.clientX) {
			coords.left = evt.clientX + document.body.scrollLeft - document.body.clientLeft;
			coords.top = evt.clientY + document.body.scrollTop - document.body.clientTop;
			if(document.body.parentElement && document.body.parentElement.clientLeft) {
				var bodParent = document.body.parentElement;
				coords.left += bodParent.scrollLeft - bodParent.clientLeft;
				coords.top += bodParent.scrollTop - bodParent.clientTop;
			}
		}
		return coords;
	}


	function findRectOwningNode(rect) {
		var i = 0;
		var len = m_vAllNodes.length;
		while(i < len) {
			var anode = m_vAllNodes[i++],
				bnode = anode.getRaphRect();
			if(bnode != null) {
				if(bnode == rect) return anode;
			}
		}
		return null;
	}

	function findTextOwningNode(textobj) {
		var i = 0;
		var len = m_vAllNodes.length;
		while(i < len) {
			var anode = m_vAllNodes[i++];
			var texts = anode.getRaphTexts();
			var j = 0;
			var len2 = texts.length;
			while(j < len2) {
				if(texts[j++] == textobj) return anode;
			}
		}
		return null;
	}

	function growCanvas(w, h) {
		iCanvasWidth = Math.max(w + 2, iCanvasWidth + 2);
		iCanvasHeight = Math.max(h + 2, iCanvasHeight + 2);
		m_Canvas.setSize(iCanvasWidth, iCanvasHeight);
		m_CanvasRect.attr({
			x: 0,
			y: 0,
			width: iCanvasWidth,
			height: iCanvasHeight,
			r: 10
		}).attr({
			fill: canvasbgcol,
			stroke: "none"
		}).toBack();
	}

	function drawLine(iXFrom, iYFrom, iXTo, iYTo) {
		m_Canvas.path("M" + iXFrom + " " + iYFrom + "L" + iXTo + " " + iYTo);
	}

	function find(sFTID) {
		var n;
		for(var i = 0; i < m_vAllNodes.length; ++i) {
			n = m_vAllNodes[i];
			if(n.getFTID().toLowerCase() == sFTID.toLowerCase()) return n;
		}
		return null;
	}

	function findName(sName) {
		var n;
		for(var i = 0; i < m_vAllNodes.length; ++i) {
			n = m_vAllNodes[i];
			if(n.getName() != null)
				if(n.getName().toLowerCase() == sName.toLowerCase()) return n;
		}
		return null;
	}

	function findOrCreate(sFTID) {
		if(sFTID == null) return null;
		var nFound = find(sFTID);
		if(nFound == null) {
			nFound = new Node(sFTID);
		}
		return nFound;
	}

	function findOrCreateName(sName) {
		var nFound = findName(sName);
		return findOrCreate(findName(sName));
	}

	function connectParentChild(p, c) {
		var bFound = false;
		var ch = p.getChildren();
		var i = 0;
		var len = ch.length;
		while(i < len) {
			if(ch[i++] == c) {
				bFound = true;
				break;
			}
		}
		if(bFound == false) ch.push(c);
		bFound = false;
		var pa = c.getParents();
		var i = 0;
		var len = pa.length;
		while(i < len) {
			if(pa[i++] == p) {
				bFound = true;
			}
		}
		if(bFound == false) pa.push(p);
	}

	function connectSpouses(s1, s2) {
		if(s1.hasPartner(s2));
		else s1.getSpouses().push(s2);
		if(s2.hasPartner(s1));
		else s2.getSpouses().push(s1);
	}

	function printTreeFromNode(sID) {
		var n = find(sID);
		m_iFontLineHeight = 10;
		m_iFontLineDescent = 4;
		m_iTallestBoxSize = 0;
		if(n == null) {
			alert("Sorry, \'" + sID + "\' is not part of the tree");
			return;
		}
		n.countParentGenerations();
		n.countChildrenGenerations();
		n.calcParentBranchWidths();
		n.calcChildrenBranchWidths();
		n.graphMe(0, 0);
		n.graphChildren(n.m_iMyBranchWidth / 2, 1);
		n.graphConnections();
	}

	function getPixelsPerLine() {
		return 14;
	}

	function resetLine() {
		m_yLine = 0;
	}

	function getLine() {
		return m_yLine;
	}

	function incLine() {
		++m_yLine;
	}

	function makeGraphBox(bPrintIt, theBox, sAddString, node) {
		if(bPrintIt) {
			var w = 0;
			var theRaphText = m_Canvas.text(0, 0, sAddString != null ? decodeURI(sAddString) : "");
			theRaphText.attr({
				"fill": nodetextcolour
			});
			node.getRaphTexts().push(theRaphText);
			w = theRaphText.getBBox().width;
			w = 0;
			theRaphText.attr({
				x:  25 + theBox.x + (theBox.width - w) / 2 + m_iBoxBufferSpace - 2,
				y: m_iToolbarYPad + theBox.y + getLine() * getPixelsPerLine()
			}).toFront();

			var toolbardiv = node.getToolbarDiv();
			if(bShowToolbar && (toolbardiv != null)) {
				var tbw = parseInt(toolbardiv.style.width);
				var tbh = parseInt(toolbardiv.style.height);
				toolbardiv.style.visibility = "visible";
				if(m_bCornered) {
					toolbardiv.style.left = m_iToolbarXPos + theBox.x + 'px';
					toolbardiv.style.top = m_iToolbarYPos + theBox.y + 'px';
				} else {
					toolbardiv.style.left = m_iToolbarXPos + theBox.x + (theBox.width - tbw) / 2 + 'px';
					toolbardiv.style.top = m_iToolbarYPos + theBox.y + 'px';
				}
			}
			var thumbnaildiv = node.getThumbnailDiv();
			if(thumbnaildiv != null) {
				var tbw = parseInt(thumbnaildiv.style.width);
				var tbh = parseInt(thumbnaildiv.style.height);
				thumbnaildiv.style.visibility = "visible"; // hidden
				thumbnaildiv.style.left = theBox.x + 1 + 'px';
				thumbnaildiv.style.top = theBox.y + 1 + 'px';
			}
			theRaphText.click(function() {
				if(bRefocusOnClick) {
					var n = findTextOwningNode(this);
					if(n != null) {
						text_sStartName.value = n.getName();
						redrawTree();
					}
				}
			}).mouseover(function(ev) {
				var n = findTextOwningNode(this);
				if(n != null) {
					var tnd = n.getThumbnailDiv();
					tnd.style.visibility = "visible";
				}
			}).mouseout(function() {
				var n = findTextOwningNode(this);
				if(n != null) {
					var tnd = n.getThumbnailDiv();
					tnd.style.visibility = "visible"; // hidden
				}
			});
			incLine();
		} else {
			var w = 0;
			if(sAddString != null) {
				var temptxt = m_Canvas.text(0, 0, decodeURI(sAddString));
				w = temptxt.hide().getBBox().width;
				temptxt.remove();
			}
			w += 2 * m_iBoxBufferSpace + 1;
			if(w > theBox.width) theBox.width = w;
			theBox.height += getPixelsPerLine();
		}
		return theBox;
	}

	function isValidDate(sDate) {
		var dlen = sDate.length;
		if((dlen != 4) && (dlen != 6) && (dlen != 8)) return false;
	}

	function createTreeFromArray(sArray) {
		var n = null,
			sKey = null;
		var i = 0;
		var len = sArray.length;
		while(i < len) {
			var sLine = sArray[i++];
			var sTokens = sLine.split("=");
			if((sTokens.length < 1) || (sTokens[0].charAt(0) == '#')) continue;
			sKey = sTokens[0].toLowerCase();
			if(sKey == "esscottiftid") {
				n = findOrCreate(sTokens[1]);
			} else if(sKey == "name") {
				n.setName(sTokens[1]);
			} else if(sKey == "imageurl") {
				n.setImageURL(sTokens[1]);
			} else if(sKey == "toolbar") {
				n.setToolbarDiv(sTokens[1]);
			} else if(sKey == "thumbnaildiv") {
				n.setThumbnailDiv(sTokens[1]);
			} else if(sKey == "shortinfourl") {
				n.setShortInfoURL(sTokens[1]);
			} else if(sKey == "longinfourl") {
				n.setLongInfoURL(sTokens[1]);
			} else if(sKey == "male") {
				n.setGender("m");
			} else if(sKey == "female") {
				n.setGender("f");
			} else if(sKey == "spouse") {
				n.setSpouse(sTokens[1]);
			} else if(sKey == "maiden") {
				n.setMaiden(sTokens[1]);
			} else if(sKey == "year") {
				n.setBirthYear(sTokens[1]);
			} else if(sKey == "birthday") {
				n.setBirthday(sTokens[1]);
			} else if(sKey == "deathday") {
				n.setDeathday(sTokens[1]);
			} else if(sKey == "parent") {
				n.addParent(sTokens[1]);
			} else if(sKey == "child") {
				n.addChild(sTokens[1]);
			} else alert("Error in family tree file: " + sTokens[0] + " " + sTokens[1]);
		}

	}

	function freeNodesAllocatedTexts() {
		var i = 0;
		var len = m_vAllNodes.length;
		while(i < len) {
			m_vAllNodes[i].setRaphTexts(null);
			m_vAllNodes[i].setRaphTexts(new Array());
			i++;
		}
	}

	function resetObjectStates() {
		hoverpic.style.visibility = "hidden";
		var i = 0;
		var len = m_vAllNodes.length;
		while(i < len) {
			var n = m_vAllNodes[i++];
			var aToolbarDiv = n.getToolbarDiv();
			if(aToolbarDiv == null) continue;
			aToolbarDiv.style.visibility = "hidden";
		}
	}

	function loadImages() {
		var i = 0;
		var len = m_vAllNodes.length;
		while(i < len) {
			var n = m_vAllNodes[i++];
			var sUrl = n.getImageURL();
			if(sUrl == null) continue;
			var img = new Image();
			n.setImage(img);
			img.style.visibility = "hidden";
			img.src = encodeURI(sUrl);
			img.onload = function() {
				var max_height = iMaxHoverPicHeight;
				var max_width = iMaxHoverPicWidth;
				var height = this.height;
				var width = this.width;
				var ratio = height / width;
				if(height > max_height) {
					ratio = max_height / height;
					height = height * ratio;
					width = width * ratio;
				}
				if(width > max_width) {
					ratio = max_width / width;
					height = height * ratio;
					width = width * ratio;
				}
				this.width = width;
				this.height = height;
			};
		}
	}

	function loadDivs() {
		var i = 0;
		var len = m_vAllNodes.length;
		while(i < len) {
			var n = m_vAllNodes[i++];
			var aToolbarDiv = n.getToolbarDiv();
			if(aToolbarDiv == null) continue;
			aToolbarDiv.style.visibility = "hidden";
		}
	}

	function loadShortInfo() {
		var i = 0;
		var len = m_vAllNodes.length;
		while(i < len) {
			var n = m_vAllNodes[i++];
			var sUrl = n.getShortInfoURL();
			if(sUrl == null) continue;
		}
	};

	function loadLongInfo() {
		var i = 0;
		var len = m_vAllNodes.length;
		while(i < len) {
			var n = m_vAllNodes[i++];
			var sUrl = n.getLongInfoURL();
			if(sUrl == null) continue;
		}
	};
	this.setMaxHoverPicWidth = function(iWidth) {
		iMaxHoverPicWidth = iWidth;
	};
	this.setMaxHoverPicHeight = function(iHeight) {
		iMaxHoverPicHeight = iHeight;
	};
	this.setOneNamePerLine = function(bState) {
		bOneNamePerLine = bState;
	};
	this.setOnlyFirstName = function(bState) {
		bOnlyFirstName = bState;
	};
	this.setBirthAndDeathDatesOnlyYear = function(bState) {
		bBirthAndDeathDatesOnlyYear = bState;
	};
	this.setBirthDatePrefix = function(bState) {
		bBirthDatePrefix = bState;
	};
	this.setDeathDatePrefix = function(bState) {
		bDeathDatePrefix = bState;
	};
	this.setBirthAndDeathDates = function(bState) {
		bBirthAndDeathDates = bState;
	};
	this.setConcealLivingDates = function(bState) {
		bConcealLivingDates = bState;
	};
	this.setDeath = function(bState) {
		bDeath = bState;
	};
	this.setShowSpouse = function(bState) {
		bShowSpouse = bState;
	};
	this.setShowOneSpouse = function(bState) {
		bShowOneSpouse = bState;
	};
	this.setVerticalSpouses = function(bState) {
		bVerticalSpouses = bState;
	};
	this.setMaidenName = function(bState) {
		bMaidenName = bState;
	};
	this.setShowGender = function(bState) {
		bShowGender = bState;
	};
	this.setDiagonalConnections = function(bState) {
		bDiagonalConnections = bState;
	};
	this.setRefocusOnClick = function(bState) {
		bRefocusOnClick = bState;
	};
	this.setShowToolbar = function(bState) {
		bShowToolbar = bState;
	};
	this.setNodeRounding = function(iRadius) {
		m_iNodeRounding = iRadius;
	};
	this.setToolbarYPad = function(iYPad) {
		m_iToolbarYPad = iYPad;
	};
	this.setMinBoxWidth = function(iMinWidth) {
		m_iMinBoxWidth = iMinWidth;
	};
	this.setToolbarPos = function(bCorner, iX, iY) {
		m_bCornered = bCorner;
		m_iToolbarXPos = iX;
		m_iToolbarYPos = iY;
	};
	this.getOneNamePerLine = function() {
		return bOneNamePerLine;
	};
	this.getOnlyFirstName = function() {
		return bOnlyFirstName;
	};
	this.getBirthAndDeathDates = function() {
		return bBirthAndDeathDates;
	};
	this.getConcealLivingDates = function() {
		return bConcealLivingDates;
	};
	this.getDeath = function() {
		return bDeath;
	};
	this.getShowSpouse = function() {
		return bShowSpouse;
	};
	this.getShowOneSpouse = function() {
		return bShowOneSpouse;
	};
	this.getVerticalSpouses = function() {
		return bVerticalSpouses;
	};
	this.getMaidenName = function() {
		return bMaidenName;
	};
	this.getShowGender = function() {
		return bShowGender;
	};
	this.getDiagonalConnections = function() {
		return bDiagonalConnections;
	};
	this.getRefocusOnClick = function() {
		return bRefocusOnClick;
	};
	this.getShowToolbar = function() {
		return bShowToolbar;
	};
	this.getNodeRounding = function() {
		return m_iNodeRounding;
	};
	this.getNodeRounding = function() {
		return m_iNodeRounding = iRadius;
	};
	this.getToolbarYPad = function() {
		return m_iToolbarYPad;
	};
	this.getMinBoxWidth = function() {
		return m_iMinBoxWidth;
	};
	this.getToolbarPosX = function() {
		return m_iToolbarXPos;
	};
	this.getToolbarPosY = function() {
		return m_iToolbarYPos;
	};
	this.getToolbarCornered = function() {
		return m_bCornered;
	};
	this.onFocusPersonChanged = function(e) {};
})();
