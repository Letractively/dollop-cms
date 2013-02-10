/**
 * jQuery Smooth Transitions
 *
 * @Author: Benjamin Leffler <btleffler@gmail.com>
 * @Date: 10/21/11
 */

/*
    NOTES:
    Works!
    The only real issue is that if the css rule is applied by an id, I have to remove that id so that CSS doesn't take over for the hover. If I remove the id, it could mess up other javascript/css rules. Another option would be to remove the CSSRule altogether, but that could cause browser issues again. I'll look into it a bit.
*/

(function($) {
    var styleSheets = document.styleSheets;
    var hoverRules = {}; // Where we'll store all the rules that we're transitioning to
    var normalRules = {}; // Where we'll store the original rules
    var combinedRules = {}; // Where we'll store all the extended rules
    var i, j, k, sheet, rule, selector, style, property, value;

    // Lets get our important rules!
    for (i = 0; i < styleSheets.length; i++) {
        sheet = styleSheets[i];

        if (sheet.rules) {
            // IE
            rules = sheet.rules;
        } else {
            rules = sheet.cssRules;
        }

        for (j = 0; j < rules.length; j++) {
            rule = rules[j];
            selector = rule.selectorText;

            // Figure out if it's got a :hover selector
            if (selector.indexOf(":hover") !== -1) {
                selector = selector.substring(0, selector.indexOf(":hover"));

                // Save the rule and the style that should be applied
                style = rule.style.cssText.split(';');

                hoverRules[selector] = {
                    css: {},
                    styleSheet: sheet // Easier searching
                };

                // Go through the css text and make it
                // and object we can plug into .css()
                for (k = 0; k < style.length; k++) {
                    // Split it up again
                    style[k] = style[k].split(':');

                    if (style[k].length === 2) {
                        property = style[k][0].replace(/^\s+|\s+$/g, '');
                        value = style[k][1].replace(/^\s+|\s+$/g, '');
                    } else {
                        continue;
                    }

                    // Add it to the hoverRules object
                    if (property !== "" && value !== "") {
                        hoverRules[selector].css[property] = value;
                    }
                }
            }
        }
    }

    // Find the normal rules for each hover we found
    for (rule in hoverRules) {
        if (hoverRules.hasOwnProperty(rule)) {
            sheet = hoverRules[rule].styleSheet;
            selector = rule;

            if (sheet.rules) {
                // IE
                rules = sheet.rules;
            } else {
                rules = sheet.cssRules;
            }

            // Now we search for that rule!
            for (i = 0; i < rules.length; i++) {
                rule = rules[i];

                // Check if we have a match
                if (rule.selectorText === selector) {
                    // Save the rule and the style that should be applied
                    style = rule.style.cssText.split(';');

                    // We won't need the parent style sheet to search this
                    // time but we might as well keep things consistent
                    normalRules[selector] = {
                        css: {}
                    };

                    // Go through the css text and make it
                    // and object we can plug into .css()
                    for (k = 0; k < style.length; k++) {
                        // Split it up again
                        style[k] = style[k].split(':');

                        if (style[k].length === 2) {
                            property = style[k][0].replace(/^\s+|\s+$/g, '');
                            value = style[k][1].replace(/^\s+|\s+$/g, '');
                        } else {
                            continue;
                        }

                        // Add it to the hoverRules object
                        if (property !== "" && value !== "") {
                            normalRules[selector].css[property] = value;
                        }
                    }

                    // If we found it, break out of that loop
                    break;
                }
            }
        }
    }

    // Now that we have the normal rules, and the hover rules,
    // we need to extend them so that the hover effect looks
    // like it normally would if CSS rendered it
    for (rule in normalRules) {
        if (normalRules.hasOwnProperty(rule)) {
            // Keep it consistent again
            combinedRules[rule] = { css: {} };

            // Extend them
            $.extend(
            true, combinedRules[rule].css, normalRules[rule].css, hoverRules[rule].css);
        }
    }

    jQuery.fn.SmoothTransition = function() {
        return this.each(function() {
            var $this = $(this); // Lazy
            var $parent = $this.parent();
            var i, j, rule, $elems, $newElem, selector, theStyle, theHoverStyle;

            // We need to find the hoverRule that applies to our element
            for (selector in hoverRules) {
                if (hoverRules.hasOwnProperty(selector)) {
                    rule = hoverRules[selector];

                    // Check if it applies
                    $elems = $(selector);

                    for (i = 0; i < $elems.length; i++) {
                        // Are we looking at the same node?
                        if ($elems.eq(i)[0] === $this[0]) {
                            // Valid match
                            // Add the new element under this one
                            $newElem = $this.clone(true, true);
                            theStyle = normalRules[selector].css;
                            theHoverStyle = combinedRules[selector].css;

                            // Add the style
                            $newElem.css(theHoverStyle);

                            // Position the new element
                            $newElem.css({
                                position: "absolute",
                                top: $this.position().top - $parent.offset().top,
                                left: $this.position().left - $parent.offset().left,
                                "z-index": 500,
                                opacity: 0
                            });

                            // Add the style to the original element
                            $this.css(theStyle).css({
                                position: "relative",
                                "z-index": 501
                            });

                            // Remove the stuff that would trigger the old CSS
                            if (selector.charAt(0) === '#') {
                                // it's an id :-(
                                $this.attr("id", "");
                                $newElem.attr("id", "");
                            } else {
                                $this.removeClass(selector.substring(1));
                                $newElem.removeClass(selector.substring(1));
                            }

                            // Append the new element to the parent
                            $parent.css("position", "relative");
                            $newElem.prependTo($parent);

                            // All we need to do is fade out the element on top (original)
                            // And fade in the new one
                            $this.hover(function() {
                                $this.stop().clearQueue().animate({
                                    opacity: 0
                                }, "slow");

                                $newElem.stop().clearQueue().animate({
                                    opacity: 1
                                }, "fast");
                            }, function() {
                                $this.stop().clearQueue().animate({
                                    opacity: 1
                                }, "fast");

                                $newElem.stop().clearQueue().animate({
                                    opacity: 0
                                }, "slow");
                            });

                            // Get out of there
                            break;
                        }
                    }
                }
            }
        });
    };
})(jQuery);


$(document).ready(function() {
    $(".links  li").SmoothTransition();
});
