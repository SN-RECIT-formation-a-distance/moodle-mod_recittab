const allCSS = [...document.styleSheets]
  .map(styleSheet => {
    try {
      return [...styleSheet.cssRules]
        .map(rule => rule.cssText)
        .join('');
    } catch (e) {
      console.log('Access to stylesheet %s is denied. Ignoring...', styleSheet.href);
    }
  })
  .filter(Boolean)
  .join('\n');
console.log(allCSS);
if (allCSS.includes("recit_tab_0")){
    console.log("Contient le css");
}
else {
    allCSS.insertRule ("body {background:red;}", 0);
}
    

