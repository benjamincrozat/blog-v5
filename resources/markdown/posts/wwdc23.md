---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/35/wwdc23_p75enu.png
Title: "A summary of web related sessions from WWDC23"
Description: "Using a well-known Large Language Model, I managed to summarize every session from WWDC23 that's related to web development."
Published at: 2023-06-07
Modified at: 2023-06-11
Categories: css, javascript, tools
---

## Introduction

**WWDC stands for "Worldwide Developers Conference".** Think of it as a huge gathering, hosted by Apple each year. It's a bit like a big party for anyone who creates stuff for their devices.

But it's not just about native apps. Apple also talks about Safari and web technologies during WWDC.

This year, it's a bit special, though. They're also showing how to create great experiences with web technologies on their Apple Vision Pro headset.

First, I suggest taking a look at the super exhaustive [release notes for Safari](https://developer.apple.com/documentation/safari-release-notes).

## What's new in web apps

1. **Web apps on Mac are now available**, allowing users to have dedicated and focused experiences with their frequently used websites.
2. Adding websites as web apps is done by adding them to the Dock on macOS.
3. **Web apps on Mac have a default behavior that works well for most websites**, but **developers can customize their app's behavior** using a web app manifest.
4. **Web app scope can be adjusted to control how links within the app open**, either within the web app or in the default browser.
5. **Authentication state should be saved in cookies** to ensure smooth user experiences when logging into web apps.
6. **Web apps on Mac and iOS support notifications**, including sounds, badging, and integration with Focus modes. (Learn more about the [Push API](https://developer.mozilla.org/en-US/docs/Web/API/Push_API).)
7. **The web app manifest’s "id" key allows for syncing Focus modes** and management of distinct web apps under the same domain.
8. **New APIs are available in WebKit**, including the **[User Activation API](https://developer.mozilla.org/en-US/docs/Web/API/UserActivation)**, **updated [Fullscreen API](https://developer.mozilla.org/en-US/docs/Web/API/Fullscreen_API)**, and **preliminary support for the [Screen Orientation API](https://developer.mozilla.org/en-US/docs/Web/API/Screen/orientation)**.

Watch ["What's new in web apps"](https://developer.apple.com/videos/play/wwdc2023/10120/) to learn more.

## Meet Safari for spatial computing

1. **Safari for spatial computing uses the same WebKit engine** and supports web standards, enabling a seamless browsing experience.
2. **The platform supports direct and indirect gestures**, allowing users to interact with websites using eye and hand gestures.
3. Interactive regions in Safari indicate interactivity and are generated automatically by WebKit based on accessible markup and CSS styling.
4. **Full-screen windows on Safari can be resized on visionOS and may sometimes be larger than the reported screen dimensions.**
5. Performance optimizations matter in animations and scrolling, so **using [passive scroll event listeners](https://developer.chrome.com/en/docs/lighthouse/best-practices/uses-passive-event-listeners/) and [requestAnimationFrame](https://developer.mozilla.org/en-US/docs/Web/API/window/requestAnimationFrame) is recommended**.
6. **Integrating 3D content** like AR Quick Look or HTML model element with USDZ files **can enhance a user's browsing experience**.
7. **The W3C standard [WebXR](https://developer.mozilla.org/en-US/docs/Web/API/WebXR_Device_API) provides immersive scenes on the web using WebGL**, enabling developers to experiment with immersive web experiences.

Watch ["Meet Safari for spatial computing"](https://developer.apple.com/wwdc23/10279) to learn more.

## What's new in CSS

- **[:user-valid](https://developer.mozilla.org/en-US/docs/Web/CSS/:user-valid) and [:user-invalid](https://developer.mozilla.org/en-US/docs/Web/CSS/:user-invalid) pseudo-classes:** These classes help manage the validation of form inputs in a more intuitive way than previously possible. They allow styling based on whether the user has filled out a form field correctly or not.
- **Enhancements to the [:has() pseudo-class](https://developer.mozilla.org/en-US/docs/Web/CSS/:has):** The power of :has() has been increased to work with even more pseudo-classes, allowing more versatile styling depending on the presence of specific elements or states. For instance, you can now style elements based on the presence of a particular language with :has(:lang()).
- **[:dir](https://developer.mozilla.org/en-US/docs/Web/CSS/:dir) pseudo-class:** This class is crucial for supporting different text flow directions based on the language being used, often abbreviated as LTR and RTL.
- **New line-height units:** The [lh](https://developer.mozilla.org/en-US/docs/Web/CSS/length#lh) and [rlh](https://developer.mozilla.org/en-US/docs/Web/CSS/length#rlh) units in CSS are new additions that allow designers to create a more cohesive and rhythmically consistent layout by relating elements to the line-height of the typography.
- **[font-size-adjust](https://developer.mozilla.org/en-US/docs/Web/CSS/font-size-adjust):** This CSS property adjusts the visual size of a font, allowing for greater visual consistency across different font styles and sizes.
- **[text-box-trim](https://css-tricks.com/leading-trim-the-future-of-digital-typesetting/):** This property allows for trimming of extra space in a text box, helping to resolve common alignment issues in web typography.
- **[Counter styles](https://developer.mozilla.org/en-US/docs/Web/CSS/@counter-style):** CSS now allows you to define custom numbering systems and styles for HTML lists.
- **Further support for [Open Type features](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_fonts/OpenType_fonts_guide), [Media Queries range syntax](https://css-tricks.com/the-new-css-media-query-range-syntax/), [boolean logic](https://css-tricks.com/logical-operations-with-css-variables/), [@property](https://developer.mozilla.org/en-US/docs/Web/CSS/@property), [CSS Nesting](https://webkit.org/blog/13813/try-css-nesting-today-in-safari-technology-preview/), and much more.**

Watch ["What's new in CSS"](https://developer.apple.com/wwdc23/10121) to learn more.

## Rediscover Safari developer features

1. **Inspecting a Web Page**: To inspect elements on a web page, control-click on the page and select "Inspect Element" from the context menu. You can also enable Web Inspector by checking the "Show features for web developers" checkbox in Safari's Settings under the Advanced tab.

2. **Responsive Design Mode**: Access this mode from the Develop menu by selecting "Enter Responsive Design Mode." It lets you test your page's responsiveness across various screen sizes, change pixel ratios, and ensure pages reflow correctly.

3. **Simulators**: iOS, iPadOS, and visionOS simulators can be accessed from Safari's Develop menu under the "Open with Simulator" item. This enables testing web content on different platforms without needing physical devices.

4. **Inspecting iOS, iPadOS, tvOS, and visionOS devices**: To inspect web content from devices, enable the "Web Inspector" setting on the device, connect it to your Mac, and choose the content you want to inspect from the Develop menu in Safari. You can connect wirelessly by enabling "Connect via Network" in the Develop menu.

5. **Inspecting Web Content in Apps**: New APIs are available for macOS 13.3, iOS 16.4, and later, enabling inspectable content in your apps. This API is available for both WKWebView and JSContext.

6. **WebDriver for Automated Testing**: [WebDriver](https://www.selenium.dev/documentation/webdriver/), a cross-browser API, allows you to automate the testing process for web content in Safari and other browsers. You can interact with WebDriver using third-party libraries like Selenium.

7. **Exploring Future Features**: Access and enable new features in Safari using the "Feature Flags" menu (previously called "Experimental Features") under the Develop menu. This allows you to explore upcoming web technologies before they are released.

Watch ["Rediscover Safari developer features"](https://developer.apple.com/wwdc23/10262) to learn more.

## Explore media formats for the web

Here's an overview of what's covered:

1. **Modern image formats introduced in Safari 17: WebP, JPEG-XL, AVIF, and HEIC.**
2. Using the HTML picture element to specify alternative sources and let the browser choose the best format.
3. A brief history of video presentation on websites and the introduction of Media Source Extension (MSE).
4. **The disadvantages of MSE and the introduction of Managed Media Source API in Safari 17**.
5. Migrating from MSE to Managed Media Source: making necessary changes in the HTML and JavaScript code.
6. Using AirPlay to stream video to TVs, even when using MSE or Managed Media Source.
7. **The HLS.js framework for handling videos on different browsers.**

Watch ["Explore media formats for the web"](https://developer.apple.com/wwdc23/10122) to learn more.

## What's new in Web Inspector

1. **Typography Inspection tools**: The Font panel in Web Inspector allows developers to inspect the properties and capabilities of the font used on a selected element, as well as supported font feature properties and their values. This panel now shows warnings for synthetic bold or oblique styles and provides a list of supported variation axes for variable fonts.

2. **Emulating User Preferences**: The new User Preference Overrides popover in the Elements tab enables developers to override user preferences just for the inspected page while Web Inspector is open. Preferences like color scheme, reduced motion, and increased contrast can be emulated easily using this tool.

3. **New Element badges**: The node tree view in the Elements tab now shows badges next to elements that act as CSS Flex or CSS Grid containers, as well as badges next to scroll containers to help identify unwanted scroll. 

4. **Breakpoints enhancements**: Breakpoints can be configured with various properties to control when they are hit and even run actions when they do. This includes setting conditions, ignoring the breakpoint for a specific number of times, and running actions like evaluating a JavaScript expression or logging messages to the console without pausing.

Watch ["What's new in Web Inspector"](https://developer.apple.com/wwdc23/10118) to learn more.

## What's new in Safari extensions

1. **Safari 17 continues to support content blockers**, share extensions, app extensions, and web extensions, with web extensions being the future of browser customization.
2. **[Manifest v2 and v3 web extensions](https://developer.mozilla.org/en-US/docs/Mozilla/Add-ons/WebExtensions/manifest.json) are both supported** in Safari 17.
3. Web extensions can customize Safari on iOS, iPadOS, macOS, and visionOS.
4. **Content blockers now support [:has() selectors](https://developer.mozilla.org/en-US/docs/Web/CSS/:has)**, allowing more precise targeting of parent elements based on their children.
5. **Declarative Net Request in Safari 16.4 allows modifying request headers**, resulting in enhanced power efficiency and increased user privacy and security.
6. **The [declarativeNetRequest.setExtensionActionOptions](https://developer.mozilla.org/en-US/docs/Mozilla/Add-ons/WebExtensions/API/declarativeNetRequest/setExtensionActionOptions) API can configure badge text to display action counts**, letting users monitor extension activity.
7. **[RegisterContentScript API](https://developer.mozilla.org/en-US/docs/Mozilla/Add-ons/WebExtensions/API/scripting/registerContentScripts) enables more flexible management of content scripts and more advanced features for extensions.**
8. Session storage area in web extensions allows for fast, efficient storage of data across nonpersistent background page loads.
9. **A single SVG icon can be used for extensions in Safari 16.4**, which will automatically scale for sharp display at any size.
10. **Safari app extensions gain per-site permissions**, giving users more control over websites the extension can access.
11. **Users can control which extensions have access to Private Browsing windows and tabs in Safari 17.**
12. Profiles in Safari help keep browsing data separated, and **users can choose which extensions to activate per profile.**
13. The Extensions pane in Safari settings gets updated to list the profiles where an extension is active.

Watch ["What's new in Safari extensions"](https://developer.apple.com/wwdc23/10119) to learn more.

