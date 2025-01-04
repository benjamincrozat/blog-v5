---
Title: "Frank de Jonge's perspective on mocking frameworks"
Description: "Once a fan, Frank de Jonge now suggests ditching mocking frameworks for real implementations to simplify code testing."
Canonical: https://blog.frankdejonge.nl/testing-without-mocking-frameworks/
Published at: 2023-08-10
Categories: testing
---

## Introduction

In this insightful article, Frank de Jonge shares his evolved views on code testing and specifically on the use of mocking frameworks.

This article by Frank de Jonge delves into the evolution of his own coding practices, focusing on the transition from heavy reliance on mocking frameworks to a preference for testing without them.

He highlights the high cost of change when using mocking frameworks, especially in the maintenance stage of code. He then suggests several best practices and provides detailed examples of how to test without using mocking frameworks. These include creating your own "fakes" or test doubles, and defining meaningful boundaries.

Frank believes that this approach creates clearer high-level code, allows for more predictable IDE refactors and ensures the tests do not contain implementation details.

