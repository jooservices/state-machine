# Basic Concepts

| Term | Meaning |
|:---|:---|
| Subject | Any PHP object holding the state string |
| Graph | Named configuration of states + transitions |
| Property | Subject field that stores the state |
| Transition | Named move from one or more `from` states to a `to` state |
| Guard | Class that may reject a transition |
| Callback | Class that runs before/after a transition |
| Accessor | Strategy for reading/writing the state property |
