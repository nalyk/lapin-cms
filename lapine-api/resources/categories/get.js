var category = this;
category.group = category.name;
var i = 1;

dpd.categories.get({parrent: category.id, $limitRecursion: 1}, function(items) {
  if(items && items.length) {
      category.children = items;
      category.group = category.name;
  }
});

if (category.parrent !== "null") {
    dpd.categories.get({id: category.parrent, $limitRecursion: 1}, function(items) {
        if (items !== "null") {
            category.parrent = items;
            category.group = items.name+'-'+category.id;
            i++;
        }
    });
}